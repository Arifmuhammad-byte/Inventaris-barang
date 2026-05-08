<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Barang;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use App\Models\BarangUnit;

class BarangCrud extends Component
{
    use WithFileUploads;
    public $barangUnits = [];
    public $selectedUnitId = null;
    public $kondisiUnit = [];
    public $kode_barang_prefix;
    public $kode_barang = []; // array untuk multi kode
    public $showModal = false;

    public $foto;
    public $foto_lama;

    public $barang_id;
    public $nama_barang;
    public $kategori;
    public $lokasi;
    public $jumlah_total;
    public $kondisi_baik;
    public $kondisi_rusak_ringan;
    public $kondisi_rusak_berat;
    public $keterangan;

    public $search = '';
    public $filterKategori = '';
    public $filterLokasi = '';

    public $kategoriOptions = [];
    public $lokasiOptions = [];


   protected $rules = [
    'nama_barang' => 'required|string|max:100',
    'kategori' => 'required|string|max:50',
    'lokasi' => 'nullable|string|max:100',
    'jumlah_total' => 'required|integer|min:0',
    'keterangan' => 'nullable|string',
    'kode_barang_prefix' => 'required|string|max:50',
];

    public function mount()
    {
        $this->loadOptions();
        $this->loadData();
    }

    private function resetForm()
{
    $this->reset([
        'barang_id',
        'nama_barang',
        'kategori',
        'jumlah_total',
        'kondisi_baik',
        'kondisi_rusak_ringan',
        'kondisi_rusak_berat',
        'lokasi',
        'keterangan',
          'kode_barang_prefix',
    ]);
}

    // Ambil daftar kategori & lokasi unik dari DB
    public function loadOptions()
    {
        $this->kategoriOptions = Barang::select('kategori')->distinct()->pluck('kategori')->toArray();
        $this->lokasiOptions = Barang::select('lokasi')->distinct()->pluck('lokasi')->toArray();
    }

    // Load data barang sesuai search/filter
    public function loadData()
    {
        $query = Barang::query();

        if ($this->search) {
            $query->where('nama_barang', 'like', '%'.$this->search.'%');
        }

        if ($this->filterKategori) {
            $query->where('kategori', $this->filterKategori);
        }

        if ($this->filterLokasi) {
            $query->where('lokasi', $this->filterLokasi);
        }

        $this->barangs = $query->latest()->get();
    }

    public function updatedSearch()
    {
        $this->loadData();
    }

    public function updatedFilterKategori()
    {
        $this->loadData();
    }

    public function updatedFilterLokasi()
    {
        $this->loadData();
    }

    /* ===== MODAL ===== */
    public function openModal()
    {
        $this->resetForm();

        // default prefix kosong
    $this->kode_barang_prefix = '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

   public function store()
{
    $this->validate();

    /*
    ===============================
    UPLOAD FOTO
    ===============================
    */

   if ($this->foto) {

    // hapus foto lama kalau ada
    if ($this->foto_lama) {
        Storage::disk('public')->delete($this->foto_lama);
    }

    // simpan foto baru
    $namaFoto = $this->foto->store('barang', 'public');

} else {

    // pakai foto lama
    $namaFoto = $this->foto_lama;

}

    /*
    ===============================
    UPDATE DATA BARANG
    ===============================
    */

    if ($this->barang_id) {

       $barang = Barang::findOrFail($this->barang_id);

$jumlahLama = $barang->jumlah_total;
$baikLama   = $barang->kondisi_baik;

$selisih = $this->jumlah_total - $jumlahLama;

// 🔥 AUTO SYNC kondisi_baik
if ($selisih > 0) {
    // nambah unit → otomatis baik
    $this->kondisi_baik = $baikLama + $selisih;
} elseif ($selisih < 0) {
    // ngurang unit → kurangi baik
    $this->kondisi_baik = max(0, $baikLama + $selisih);
}

$barang->update([
    'nama_barang' => $this->nama_barang,
    'kategori' => $this->kategori,
    'jumlah_total' => $this->jumlah_total,
    'kondisi_baik' => $this->kondisi_baik,
    'kondisi_rusak_ringan' => $this->kondisi_rusak_ringan,
    'kondisi_rusak_berat' => $this->kondisi_rusak_berat,
    'lokasi' => $this->lokasi,
    'keterangan' => $this->keterangan,
    'foto' => $namaFoto,
]);

        /*
        ===============================
        UPDATE barang_unit TANPA DELETE
        ===============================
        */

        $units = BarangUnit::where('barang_id', $barang->id)
            ->orderBy('id')
            ->get();

        $unitCount = $units->count();

        /*
        ===============================
        UPDATE PREFIX KODE
        ===============================
        */

        $no = 1;

        foreach ($units as $unit) {

            $kodeBaru =
                $this->kode_barang_prefix
                . '-'
                . str_pad($no, 3, '0', STR_PAD_LEFT);

            $unit->update([
                'kode_barang' => $kodeBaru
            ]);

            $no++;
        }

        /*
        ===============================
        TAMBAH UNIT JIKA JUMLAH BERTAMBAH
        ===============================
        */

        if ($this->jumlah_total > $unitCount) {

            for ($i = $unitCount + 1; $i <= $this->jumlah_total; $i++) {

                BarangUnit::create([

                    'barang_id' => $barang->id,

                    'kode_barang' =>
                        $this->kode_barang_prefix
                        . '-'
                        . str_pad($i, 3, '0', STR_PAD_LEFT),

                    'kondisi' => 'Baik',

                    'status' => 'Tersedia',

                ]);
            }
        }

        /*
        ===============================
        HAPUS UNIT JIKA JUMLAH BERKURANG
        ===============================
        */

        elseif ($this->jumlah_total < $unitCount) {

            BarangUnit::where('barang_id', $barang->id)
                ->orderBy('id', 'desc')
                ->take($unitCount - $this->jumlah_total)
                ->delete();
        }

        $message = 'Data barang berhasil diperbarui.';

    }

    /*
    ===============================
    CREATE DATA BARANG
    ===============================
    */
    else {

    if (!$this->kode_barang_prefix) {
        session()->flash('message', 'Kode prefix wajib diisi');
        return;
    }

    $barang = Barang::create([
        'nama_barang' => $this->nama_barang,
        'kategori' => $this->kategori,
        'jumlah_total' => $this->jumlah_total,
        'kondisi_baik' => $this->jumlah_total,
        'kondisi_rusak_ringan' => 0,
        'kondisi_rusak_berat' => 0,
        'lokasi' => $this->lokasi,
        'keterangan' => $this->keterangan,
        'foto' => $namaFoto,
    ]);

    $prefix = $this->kode_barang_prefix;

    for ($i = 1; $i <= $this->jumlah_total; $i++) {

        BarangUnit::create([
            'barang_id' => $barang->id,
            'kode_barang' => $prefix . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
            'kondisi' => 'Baik',
            'status' => 'Tersedia',
        ]);
    }

    $message = 'Data barang berhasil disimpan.';
}

    /*
    ===============================
    NOTIF
    ===============================
    */

    $this->dispatch(
        'swal-success',
        title: 'Berhasil!',
        text: $message,
        icon: 'success'
    );

    $this->closeModal();
    $this->loadData();
    $this->resetForm();
    $this->loadOptions();
}

#[On('deleteBarang')]
public function deleteBarang($id)
{
    try {

        $barang = Barang::findOrFail($id);

        // hapus unit dulu
        BarangUnit::where('barang_id', $barang->id)
            ->delete();

        // hapus foto
        if ($barang->foto) {
            Storage::disk('public')->delete($barang->foto);
        }

        // hapus barang
        $barang->delete();

        $this->loadData();

        $this->dispatch('deleted');

    } catch (\Exception $e) {

        $this->dispatch(
            'swal-error',
            title: 'Error!',
            text: $e->getMessage(),
            icon: 'error'
        );

    }
}

public function confirmDelete($id)
{
    $this->dispatch('confirm-delete', id: $id);
}


public function edit($id)
{
    $barang = Barang::with('barangUnits')->findOrFail($id);

    $this->barang_id = $barang->id;
    $this->nama_barang = $barang->nama_barang;
    $this->kategori = $barang->kategori;
    $this->jumlah_total = $barang->jumlah_total;
    $this->kondisi_baik = $barang->kondisi_baik;
    $this->kondisi_rusak_ringan = $barang->kondisi_rusak_ringan;
    $this->kondisi_rusak_berat = $barang->kondisi_rusak_berat;
    $this->lokasi = $barang->lokasi;
    $this->keterangan = $barang->keterangan;

    $this->foto_lama = $barang->foto;
    $this->barangUnits = BarangUnit::where(
    'barang_id',
    $barang->id
     )->get();

    // 🔥 Ambil salah satu kode dari barang_unit
    $kodePertama = $barang->barangUnits
        ->pluck('kode_barang')
        ->first();

    if ($kodePertama) {

        // contoh LAB-KMP-001 → ambil LAB-KMP
        $parts = explode('-', $kodePertama);

        array_pop($parts);

        $this->kode_barang_prefix =
            implode('-', $parts);

    }

    $this->showModal = true;
}

public function pilihUnit($unitId)
{
    $unit = BarangUnit::find($unitId);

    if (!$unit) return;

    // buka modal update kondisi
    $this->dispatch(
        'openUpdateKondisi',
        unitId: $unitId
    );
}

public function selectUnit($unitId)
{
    $this->selectedUnitId = $unitId;
}

public function updateKondisiUnit($unitId)
{
    $kondisi = $this->kondisiUnit[$unitId] ?? null;

    if (!$kondisi) return;

    $unit = BarangUnit::find($unitId);

    if (!$unit) return;

    $barang = Barang::find($unit->barang_id);

    if (!$barang) return;

    $kondisiLama = $unit->kondisi;


    /*
    =========================
    UPDATE UNIT
    =========================
    */

    $unit->update([
        'kondisi' => $kondisi
    ]);

    /*
    =========================
    UPDATE DATA BARANG
    =========================
    */

    // Kurangi kondisi lama

    if ($kondisiLama == 'Baik') {
        $barang->kondisi_baik--;
    }

    if ($kondisiLama == 'Rusak Ringan') {
        $barang->kondisi_rusak_ringan--;
    }

    if ($kondisiLama == 'Rusak Berat') {
        $barang->kondisi_rusak_berat--;
    }

    // Tambah kondisi baru

    if ($kondisi == 'Baik') {
        $barang->kondisi_baik++;
    }

    if ($kondisi == 'Rusak Ringan') {
        $barang->kondisi_rusak_ringan++;
    }

    if ($kondisi == 'Rusak Berat') {
        $barang->kondisi_rusak_berat++;
    }

    if ($kondisi == 'Hilang') {

        $barang->jumlah_total--;

        $unit->update([
            'status' => 'Direservasi'
        ]);
    }


    /*
    =========================
    ANTI MINUS
    =========================
    */

    $barang->kondisi_baik =
        max(0, $barang->kondisi_baik);

    $barang->kondisi_rusak_ringan =
        max(0, $barang->kondisi_rusak_ringan);

    $barang->kondisi_rusak_berat =
        max(0, $barang->kondisi_rusak_berat);

    $barang->jumlah_total =
        max(0, $barang->jumlah_total);

    $barang->save();

    /*
    =========================
    REFRESH DATA
    =========================
    */

    $this->loadData();

    $this->barangUnits =
        BarangUnit::where(
            'barang_id',
            $unit->barang_id
        )->get();
}


   
public function render()
{
    $query = Barang::query();

    if ($this->search) {
        $query->where('nama_barang', 'like', '%'.$this->search.'%');
    }

    if ($this->filterKategori) {
        $query->where('kategori', $this->filterKategori);
    }

    if ($this->filterLokasi) {
        $query->where('lokasi', $this->filterLokasi);
    }

    $barangs = $query->latest()->get();

    return view('livewire.admin.barang-crud', compact('barangs'))
        ->layout('layouts.admin', [
            'title' => 'Inventaris Barang'
        ]);
}
}
