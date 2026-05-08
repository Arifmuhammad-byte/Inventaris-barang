<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use App\Models\Peminjaman as ModelPeminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Barang;
use App\Models\BarangUnit;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class Peminjaman extends Component
{
    use \Livewire\WithPagination;

    // Format: selectedBarang[barang_id] = ['checked' => true/false, 'jumlah' => x]
    public $selectedBarang = [];
    public $searchBarang = '';
    public $riwayat = [];
    public $search = '';
    public $filterLokasi = '';

    protected $paginationTheme = 'tailwind';

    // ================================
// TOGGLE BARANG KE KERANJANG
// ================================
public function toggleBarang($id)
{
    $barang = Barang::find($id);

    if (!$barang) return;

    // Jika sudah ada → hapus
    if (isset($this->selectedBarang[$id])) {

        unset($this->selectedBarang[$id]);

    } 
    // Jika belum ada → tambah
    else {

        $this->selectedBarang[$id] = [
            'checked' => true,
            'nama' => $barang->nama_barang,
            'stok' => $barang->kondisi_baik,
            'jumlah' => 1
        ];

    }
}

// ================================
// TAMBAH JUMLAH
// ================================
public function tambahJumlah($id)
{
    if (!isset($this->selectedBarang[$id])) return;

    $stok = $this->selectedBarang[$id]['stok'];
    $jumlah = $this->selectedBarang[$id]['jumlah'];

    if ($jumlah < $stok) {

        $this->selectedBarang[$id]['jumlah']++;

    }
}

// ================================
// KURANGI JUMLAH
// ================================
public function kurangiJumlah($id)
{
    if (!isset($this->selectedBarang[$id])) return;

    $jumlah = $this->selectedBarang[$id]['jumlah'];

    if ($jumlah > 1) {

        $this->selectedBarang[$id]['jumlah']--;

    }
}

// ================================
// HAPUS BARANG
// ================================
public function removeBarang($id)
{
    if (isset($this->selectedBarang[$id])) {

        unset($this->selectedBarang[$id]);

    }
}

    public function mount()
    {
        $guruId = session('guru_id');
        if (!$guruId) {
            return redirect()->route('guru.login');
        }

        $this->loadRiwayat();
    }

    // Load riwayat peminjaman guru
    public function loadRiwayat()
    {
        $guruId = session('guru_id');
        $this->riwayat = ModelPeminjaman::with('detailBarang.barang')
                        ->where('user_id', $guruId)
                        ->orderBy('tanggal_pinjam', 'desc')
                        ->get();
    }

  public function submit()
{
    $userId = session('guru_id');

    if (!$userId) {
        session()->flash('message', 'Session login tidak ditemukan');
        return;
    }

    $barangDipilih = array_filter(
        $this->selectedBarang,
        fn($item) => ($item['jumlah'] ?? 0) > 0
    );

    if (empty($barangDipilih)) {
        session()->flash('message', 'Pilih minimal 1 barang');
        return;
    }

    DB::beginTransaction();

    try {

        // 1. CREATE PEMINJAMAN HEADER
        $peminjaman = ModelPeminjaman::create([
            'user_id' => $userId,
            'tanggal_pinjam' => now(),
            'status' => 'Menunggu',
        ]);

        foreach ($barangDipilih as $barang_id => $data) {

            $jumlah = (int) $data['jumlah'];

            // 2. AMBIL UNIT YANG BENAR-BENAR TERSEDIA SAJA
            $units = BarangUnit::where('barang_id', $barang_id)
    ->where('status', 'Tersedia')
    ->whereRaw('LOWER(kondisi) = ?', ['baik']) // 🔥 penting
    ->orderBy('kode_barang', 'asc')
    ->limit($jumlah)
    ->lockForUpdate()
    ->get();

            // 🔥 VALIDASI STOK
            if ($units->count() < $jumlah) {

                throw new \Exception(
                    "Stok kondisi baik tidak cukup untuk barang ID $barang_id"
                );
            }

            // 4. SIMPAN DETAIL + LOCK UNIT
            foreach ($units as $unit) {

                // simpan detail peminjaman
                DetailPeminjaman::create([
                    'peminjaman_id'  => $peminjaman->id,
                    'barang_id'      => $barang_id,
                    'barang_unit_id' => $unit->id,
                    'jumlah'         => 1,
                ]);

                // 🔥 LOCK UNIT jadi Direservasi
                $unit->update([
                    'status' => 'Direservasi',
                ]);
            }
        }

        DB::commit();

        // 5. RESET UI
        $this->loadRiwayat();
        $this->reset('selectedBarang');
        $this->resetPage();

       $this->dispatch(
    'swal-success',
    title: 'Berhasil!',
    text: 'Pengajuan peminjaman berhasil dikirim.',
    icon: 'success'
);

    } catch (\Throwable $e) {

        DB::rollBack();

        logger()->error('Submit peminjaman gagal', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
        ]);

        dd($e->getMessage(), $e->getLine());
    }
}

   public function batalkan($id)
{
    DB::beginTransaction();

    try {

        $peminjaman = ModelPeminjaman::with('detailBarang')->findOrFail($id);

        // hanya bisa batalkan kalau masih Menunggu
        if ($peminjaman->status !== 'Menunggu') {
            throw new \Exception('Peminjaman tidak bisa dibatalkan');
        }

        // 1. ubah status peminjaman
        $peminjaman->update([
            'status' => 'Dibatalkan'
        ]);

        // 2. kembalikan semua unit ke Tersedia
        foreach ($peminjaman->detailBarang as $detail) {

            if ($detail->barang_unit_id) {

                $unit = BarangUnit::find($detail->barang_unit_id);

                if ($unit) {
                    $unit->update([
                        'status' => 'Tersedia'
                    ]);
                }
            }
        }

        DB::commit();

        $this->loadRiwayat();

        $this->dispatch('swal-success', [
            'title' => 'Berhasil!',
            'text' => 'Peminjaman dibatalkan dan stok dikembalikan.',
            'icon' => 'success'
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        dd($e->getMessage());
    }
}
   public function hapus($id)
{
    $guruId = session('guru_id');

    $data = ModelPeminjaman::find($id);

    if (!$data) return;

    // 🔒 pastikan milik user sendiri
    if ($data->user_id != $guruId) return;

    // ❗ hanya boleh hapus jika sudah dibatalkan
    if ($data->status !== 'Dibatalkan') return;

    $data->delete();

    $this->loadRiwayat();

    $this->dispatch('swal-success', 
        title: 'Berhasil!',
        text: 'Data berhasil dihapus.',
        icon: 'success'
    );
}

     public function render()
    {
        $guruId = session('guru_id');
        if (!$guruId) {
            return redirect()->route('guru.login');
        }

        /*
        |--------------------------------------------------------------------------
        | BARANG (PAGINATION + SEARCH)
        |--------------------------------------------------------------------------
        */
        $barangs = Barang::query()
            ->when($this->searchBarang, function ($query) {
                $query->where('nama_barang', 'like', '%' . $this->searchBarang . '%');
            })

             // 🔥 FILTER LOKASI
        ->when($this->filterLokasi, function ($query) {

            $query->where(
                'lokasi',
                $this->filterLokasi
            );

        })
            ->orderBy('nama_barang', 'asc')
            ->paginate(7);

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT (FILTER COLLECTION)
        |--------------------------------------------------------------------------
        */
        $riwayatFiltered = $this->riwayat->filter(function ($item) {

            $search = strtolower($this->search);

            if (!$search) return true;

            if (strpos(strtolower($item->status), $search) !== false) {
                return true;
            }

            foreach ($item->detailBarang as $detail) {
                if (strpos(strtolower($detail->barang->nama_barang ?? ''), $search) !== false) {
                    return true;
                }
            }

            return false;
        });

        return view('livewire.guru.peminjaman', [
            'barangs' => $barangs,
            'riwayat' => $riwayatFiltered,
        ])->layout('layouts.guru');
    }
}
