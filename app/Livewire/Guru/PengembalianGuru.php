<?php

namespace App\Livewire\Guru;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\Pengembalian;
use App\Models\DetailPengembalian;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class PengembalianGuru extends Component
{
    use WithPagination;

    public $search = '';
    protected $paginationTheme = 'tailwind';
    public $selectedDetails = [];
    
    public function updatedSelectedDetails()
{
    session()->put('selectedDetails', $this->selectedDetails);
}

public function mount()
{
    $guruId = session('guru_id');

    // Ambil session lama
    $sessionDetails = session()->get('selectedDetails', []);

    // Filter hanya yang BELUM dikembalikan
    $validDetails = DetailPeminjaman::whereIn('id', $sessionDetails)
        ->where('status', '!=', 'Dikembalikan')
        ->pluck('id')
        ->toArray();

    $this->selectedDetails = $validDetails;

    // Update session biar bersih
    session()->put('selectedDetails', $validDetails);
}

public function updatingSearch()
{
    $this->resetPage();
}

public function updatingPage()
{
    session()->put('selectedDetails', $this->selectedDetails);
}

   public function render()
{
    $guruId = session('guru_id');

    $riwayat = Peminjaman::with('detailBarang.barang')
        ->where('user_id', $guruId)
        ->when($this->search, function ($query) {
            $query->where('status', 'like', '%' . $this->search . '%')
                ->orWhereHas('detailBarang.barang', function ($q) {
                    $q->where('nama_barang', 'like', '%' . $this->search . '%');
                });
        })
        ->orderBy('tanggal_pinjam', 'desc')
        ->paginate(10)->withQueryString();

    return view('livewire.guru.pengembalianguru', [
        'riwayat' => $riwayat
    ])->layout('layouts.guru');
}


    public function kembalikan($detailId)
    {
        $detail = DetailPeminjaman::with('peminjaman', 'barang')
            ->findOrFail($detailId);

        // 🔒 Hanya bisa jika sudah disetujui admin
        if ($detail->peminjaman->status !== 'Disetujui') {
            session()->flash('message', 'Peminjaman belum disetujui admin.');
            return;
        }

        // 🔒 Hindari double return
        if ($detail->status === 'Dikembalikan') {
            session()->flash('message', 'Barang sudah dikembalikan.');
            return;
        }

        DB::beginTransaction();
        try {

            // Buat header pengembalian
          $pengembalian = Pengembalian::create([

    'peminjaman_id'
        => $detail->peminjaman_id,

    'tanggal_pengembalian'
        => now(),

    'status'
        => 'Menunggu Cek'

]);


            // Buat detail pengembalian
            DetailPengembalian::create([
                'pengembalian_id' => $pengembalian->id,
                'detail_peminjaman_id' => $detail->id,
                'barang_id' => $detail->barang_id,
                'barang_unit_id' => $detail->barang_unit_id, // 🔥 INI WAJIB
                'peminjaman_id' => $detail->peminjaman_id,
                'jumlah_kembali' => $detail->jumlah,
                  'kondisi' => null,
                  'status' => 'Belum Dicek',
                
            ]);

                        // 🔥 KEMBALIKAN STATUS UNIT (DIPINJAM → TERSEDIA)
            if ($detail->barang_unit_id) {

                $unit = \App\Models\BarangUnit::find($detail->barang_unit_id);

                if ($unit && $unit->status === 'Dipinjam') {
                    $unit->update([
                        'status' => 'Tersedia'
                    ]);
                }
            }

            // 🔥 Kembalikan stok
            $barang = $detail->barang;
            $barang->kondisi_baik += $detail->jumlah;
            $barang->save();

            // Update status detail
            $detail->status = 'Dikembalikan';
            $detail->save();


            // Cek apakah semua detail sudah kembali
            $masihAda = DetailPeminjaman::where('peminjaman_id', $detail->peminjaman_id)
                ->where('status', '!=', 'Dikembalikan')
                ->exists();

            // Jika tidak ada lagi → tandai selesai
            if (!$masihAda) {
                $detail->peminjaman->update([
                    'status' => 'Dikembalikan'
                ]);
            }

            DB::commit();

            session()->flash('message', 'Barang berhasil dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('message', 'Gagal: ' . $e->getMessage());
        }
    }

   public function ajukanPengembalian($peminjamanId)
{
    if (empty($this->selectedDetails)) {

        session()->flash(
            'message',
            'Pilih minimal 1 barang.'
        );

        return;
    }

    DB::beginTransaction();

    try {

        $peminjamanIds = [];

        foreach ($this->selectedDetails as $detailId) {

            $detail = DetailPeminjaman::with(
                'peminjaman',
                'barang',
                'barangUnit'
            )->find($detailId);

            if (!$detail) continue;

            // 🔥 CEK DUPLIKAT
            $sudahAda = DetailPengembalian::where(
                    'peminjaman_id',
                    $detail->peminjaman_id
                )
                ->where(
                    'barang_unit_id',
                    $detail->barang_unit_id
                )
                ->exists();

            if ($sudahAda) {
                continue;
            }

            $peminjamanIds[] =
                $detail->peminjaman_id;

            /*
            =========================
            BUAT HEADER
            =========================
            */

            $pengembalian = Pengembalian::firstOrCreate(
                [
                    'peminjaman_id'
                        => $detail->peminjaman_id
                ],
                [
                    'tanggal_pengembalian'
                        => now(),

                    'status'
                        => 'Dikembalikan'
                ]
            );

            /*
            =========================
            SIMPAN DETAIL
            =========================
            */

            DetailPengembalian::create([

                'pengembalian_id'
                    => $pengembalian->id,

                'detail_peminjaman_id'
                    => $detail->id,

                'barang_id'
                    => $detail->barang_id,

                'barang_unit_id'
                    => $detail->barang_unit_id,

                'peminjaman_id'
                    => $detail->peminjaman_id,

                'jumlah_kembali'
                    => $detail->jumlah,

                'kondisi' => null,
                'status'  => 'Belum Dicek',
            ]);

            /*
            =========================
            JANGAN UPDATE STOK DI SINI
            =========================
            */

            /*
            =========================
            UPDATE STATUS UNIT
            =========================
            */

            if ($detail->barangUnit) {

                $detail->barangUnit->update([
                    'status' => 'Tersedia'
                ]);
            }

            /*
            =========================
            UPDATE STATUS DETAIL
            =========================
            */

            $detail->update([
                'status' => 'Dikembalikan'
            ]);
        }

        /*
        ===================================
        CEK SEMUA DETAIL SUDAH KEMBALI
        ===================================
        */

        foreach (array_unique($peminjamanIds) as $pid) {

            $masihAda =
                DetailPeminjaman::where(
                    'peminjaman_id',
                    $pid
                )
                ->where(
                    'status',
                    '!=',
                    'Dikembalikan'
                )
                ->exists();

            if (!$masihAda) {

                Peminjaman::where(
                    'id',
                    $pid
                )->update([

                    'status'
                        => 'Dikembalikan'

                ]);
            }
        }

        DB::commit();

        /* reset checkbox */
        $this->selectedDetails = [];

        session()->forget('selectedDetails');

        $this->dispatch('$refresh');

        session()->flash(
            'message',
            'Pengembalian berhasil diajukan.'
        );

    } catch (\Exception $e) {

        DB::rollBack();

        session()->flash(
            'message',
            'Error: ' . $e->getMessage()
        );
    }
}
}
