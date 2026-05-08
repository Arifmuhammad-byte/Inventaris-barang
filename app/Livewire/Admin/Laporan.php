<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Barang;
use App\Models\DetailPeminjaman;

class Laporan extends Component
{
    public $jenis_laporan;
    public $periode_awal;
    public $periode_akhir;
    public $keterangan;
    public $filterLokasi = '';

    public $laporanData = [];

    protected $rules = [
        'jenis_laporan' => 'required',
        'periode_awal' => 'required|date',
        'periode_akhir' => 'required|date|after_or_equal:periode_awal',
    ];

    public function generateReport()
{
    $this->validate();

    // ===============================
    // LAPORAN INVENTARIS
    // ===============================
   if ($this->jenis_laporan === 'Inventaris') {

    $this->laporanData = Barang::with('barangUnits') 
        ->when($this->filterLokasi, function ($query) {
            $query->where('lokasi', $this->filterLokasi);
        })
        ->orderBy('kategori')
        ->get()
        ->map(function ($item) {

            // ambil semua kode barang 
            $kode = $item->barangUnits
           ->pluck('kode_barang') // ambil semua kode
           ->toArray();

            return (object)[
                'nama_barang' => $item->nama_barang,
                'kode_barang' => $kode, 
                'kategori' => $item->kategori,
                'lokasi' => $item->lokasi,
                'keterangan' => $item->keterangan,
            ];
        });
}

    // ===============================
    // LAPORAN PEMINJAMAN
    // ===============================
    elseif ($this->jenis_laporan === 'Peminjaman') {

$query = DetailPeminjaman::with([
    'peminjaman.user',
    'peminjaman.pengembalians.detailpengembalians',
    'barang',
    'barangUnit' // 🔥 penting
])

->whereHas('peminjaman', function ($q) {

    $q->where('status', '!=', 'Ditolak')
      ->whereBetween('tanggal_pinjam', [
          $this->periode_awal,
          $this->periode_akhir
      ]);

});

/*
🔥 FILTER LOKASI
*/

if ($this->filterLokasi) {

    $query->whereHas('barang', function ($q) {

        $q->where('lokasi', $this->filterLokasi);

    });

}

$this->laporanData = $query
->get()

->map(function ($item) {

$pengembalian =
$item->peminjaman->pengembalians ?? null;

$detailPengembalian = null;

if ($pengembalian) {

$detailPengembalian =
$pengembalian
->detailpengembalians
->where(
'detail_peminjaman_id',
$item->id
)
->first();

}

return (object)[

'name' =>
$item->peminjaman->user->name ?? '-',

'kode_barang' =>
$item->barangUnit->kode_barang ?? '-',

'nama_barang' =>
$item->barang->nama_barang ?? '-',

'tanggal_pinjam' =>
$item->peminjaman->tanggal_pinjam ?? null,

'tanggal_kembali' => 
    ($detailPengembalian && $detailPengembalian->status === 'Selesai Cek')
        ? $pengembalian?->tanggal_pengembalian
        : null,

'kondisi' =>
    ($detailPengembalian && $detailPengembalian->status === 'Selesai Cek')
        ? $detailPengembalian->kondisi
        : 'Belum di cek',

];

});

}

    // ===============================
    // LAPORAN KONDISI BARANG
    // ===============================
   elseif ($this->jenis_laporan === 'Kondisi') {

    $query = \App\Models\BarangUnit::with('barang');

    // 🔥 FILTER LOKASI (ambil dari relasi barang)
    if ($this->filterLokasi) {
        $query->whereHas('barang', function ($q) {
            $q->where('lokasi', $this->filterLokasi);
        });
    }

    $this->laporanData = $query
        ->orderBy('barang_id')
        ->orderBy('kode_barang')
        ->get()
        ->map(function ($unit) {

            return (object)[
                'kode_barang' => $unit->kode_barang,
                'nama_barang' => $unit->barang->nama_barang ?? '-',
                'kondisi' => $unit->kondisi,
                'lokasi' => $unit->barang->lokasi ?? '-',
                'keterangan' => $unit->barang->keterangan ?? '-',
            ];

        });
}
}

    // ===============================
    // EXPORT PDF
    // ===============================
     public function exportPdf()
{
    // 🔥 validasi wajib
    $this->validate([
        'jenis_laporan' => 'required',
        'periode_awal' => 'required|date',
        'periode_akhir' => 'required|date|after_or_equal:periode_awal',
    ]);

    return redirect()->route('export.pdf', [
        'type' => $this->jenis_laporan,
        'awal' => $this->periode_awal,
        'akhir' => $this->periode_akhir,
    ]);
}

    // ===============================
    // EXPORT EXCEL
    // ===============================
    public function exportExcel()
    {
        if (!$this->jenis_laporan) {
            $this->dispatch('swal-error',
                title: 'Gagal',
                text: 'Pilih jenis laporan terlebih dahulu',
                icon: 'error'
            );
            return;
        }

        return redirect()->route('export.excel', [
            'type' => $this->jenis_laporan,
            'awal' => $this->periode_awal,
            'akhir' => $this->periode_akhir,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.laporan')
            ->layout('layouts.admin');
    }
}