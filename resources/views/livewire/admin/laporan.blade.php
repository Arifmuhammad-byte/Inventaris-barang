<div class="p-6 space-y-6">

   <!-- CONTAINER -->
<div class="bg-white p-8 space-y-8 shadow-sm">

    <!-- HEADER -->
    <div class="space-y-1">
        <h1 class="text-2xl font-semibold text-gray-900">
            Laporan Sistem
        </h1>
        <p class="text-sm text-gray-500">
            Generate dan export laporan inventaris dan transaksi
        </p>
    </div>


   <!-- FORM SECTION -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- ========================= -->
    <!-- KIRI : JENIS LAPORAN -->
    <!-- ========================= -->

    <div class="space-y-2">

        <label class="text-sm font-medium text-gray-700">
            Jenis Laporan
        </label>

        <select 
            wire:model="jenis_laporan"
            class="w-full bg-gray-50 
                   px-4 py-3 
                   border border-gray-200
                   focus:outline-none 
                   focus:ring-2 
                   focus:ring-[#088395]
                   transition">

            <option value="">
                Pilih Jenis Laporan
            </option>

            <option value="Inventaris">
                Laporan inventaris barang
            </option>

            <option value="Peminjaman">
                Laporan Peminjaman & Pengembalian
            </option>

            <option value="Kondisi">
                Laporan Kondisi Barang
            </option>

        </select>

    </div>

    <!-- ========================= -->
    <!-- KANAN : FILTER LOKASI -->
    <!-- ========================= -->

    <div class="space-y-2">

        <label class="text-sm font-medium text-gray-700">
            Filter Lokasi
        </label>

        <select 
            wire:model="filterLokasi"
            class="w-full bg-gray-50 
                   px-4 py-3 
                   border border-gray-200
                   focus:outline-none 
                   focus:ring-2 
                   focus:ring-[#088395]
                   transition">

            <option value="">
                Semua Lokasi
            </option>

            <option value="Lab Fisika">
                Lab Fisika
            </option>

            <option value="Lab Kimia">
                Lab Kimia
            </option>

            <option value="Lab Biologi">
                Lab Biologi
            </option>

            <option value="Lab Komputer">
                Lab Komputer
            </option>

            <option value="Gudang Olahraga">
                Gudang Olahraga
            </option>

        </select>

    </div>


        <!-- Periode -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Periode Awal
                </label>
                <input type="date"
                    wire:model="periode_awal"
                    class="w-full bg-gray-50 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#088395] transition">
            </div>

            <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700">
                    Periode Akhir
                </label>
                <input type="date"
                    wire:model="periode_akhir"
                    class="w-full bg-gray-50 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#088395] transition">
            </div>

        </div>


        <!-- BUTTON GROUP -->
        <div class="flex flex-wrap gap-4 pt-4">

           <button wire:click="generateReport"
        wire:loading.attr="disabled"
        wire:target="generateReport"
        class="px-6 py-3 bg-[#088395] text-white font-medium 
               hover:opacity-90 transition
               flex items-center justify-center gap-2
               min-w-[180px]">

    <!-- SPINNER -->
    <svg wire:loading 
         wire:target="generateReport"
         class="animate-spin h-4 w-4"
         xmlns="http://www.w3.org/2000/svg" 
         fill="none" 
         viewBox="0 0 24 24">
        <circle cx="12" cy="12" r="10" 
                stroke="currentColor" 
                stroke-width="4" 
                class="opacity-25"/>
        <path fill="currentColor" 
              class="opacity-90"
              d="M4 12a8 8 0 018-8v8H4z"/>
    </svg>

    <!-- TEXT NORMAL -->
    <span wire:loading.remove wire:target="generateReport">
        Generate Laporan
    </span>

    <!-- TEXT LOADING -->
    <span wire:loading wire:target="generateReport">
        Loading...
    </span>

</button>



        </div>

    </div>

</div>


  <!-- PREVIEW SECTION -->
<div class="bg-white p-8 space-y-6 shadow-sm">

   <div class="flex items-center justify-between">

    <h2 class="text-xl font-semibold text-gray-900">
        Preview Data Laporan
    </h2>

    <div class="flex items-center gap-3">

        @if($laporanData && count($laporanData) > 0)
            <span class="text-sm text-gray-500">
                Total: {{ count($laporanData) }} data
            </span>

            <!-- BUTTON PRINT -->
            <button onclick="printPDF()"
                class="px-4 py-2 bg-[#088395] text-white text-sm font-medium 
                       hover:bg-[#07707a] transition flex items-center gap-2">

                <!-- ICON PRINT -->
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-4 w-4" fill="none" viewBox="0 0 24 24" 
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" 
                        stroke-width="2" 
                        d="M6 9V2h12v7M6 18h12v4H6v-4zm-2-6h16v6H4v-6z"/>
                </svg>

                Cetak / PDF
            </button>
        @endif

    </div>

</div>

  <!-- AREA PRINT -->
<div id="print-area" class="bg-white p-6">

    <!-- HEADER PRINT -->
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold uppercase">SMAN 3 OKU</h1>
        <p class="text-sm">Laporan {{ $jenis_laporan }}</p>
        <p class="text-xs text-gray-500">
            Periode: {{ $periode_awal }} s/d {{ $periode_akhir }}
        </p>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">

        @if($jenis_laporan === 'Inventaris')

        <table class="w-full text-sm border border-gray-300">
            <thead class="bg-gray-100 text-xs uppercase">
                <tr>
                    <th class="border px-3 py-2">No</th>
                    <th class="border px-3 py-2">Nama Barang</th>
                    <th class="border px-3 py-2">Kode Barang</th> 
                    <th class="border px-3 py-2">Kategori</th>
                    <th class="border px-3 py-2">Lokasi</th>
                    <th class="border px-3 py-2">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporanData as $i => $item)
                <tr>
                    <td class="border px-3 py-2">{{ $i+1 }}</td>
                    <td class="border px-3 py-2">{{ $item->nama_barang }}</td>
                    <td class="border px-3 py-2 text-xs font-mono">
                    @if(!empty($item->kode_barang))
                    <div class="flex flex-wrap gap-1">
                        @foreach($item->kode_barang as $kode)
                          <span class="bg-gray-100 px-2 py-0.5 rounded">
                    {{ $kode }}
                </span>
            @endforeach
        </div>
    @else
        -
    @endif
</td>
                    <td class="border px-3 py-2">{{ $item->kategori }}</td>
                    <td class="border px-3 py-2">{{ $item->lokasi ?? '-' }}</td>
                    <td class="border px-3 py-2">{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-400">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
                {{-- ================= TOTAL INVENTARIS ================= --}}
@if(count($laporanData) > 0)

@php
    // total semua unit (dari jumlah kode barang)
    $totalSemua = collect($laporanData)->sum(function($item) {
        return is_array($item->kode_barang) ? count($item->kode_barang) : 0;
    });

    // total per lokasi
    $perLokasi = collect($laporanData)
        ->groupBy('lokasi')
        ->map(function($items) {
            return $items->sum(function($item) {
                return is_array($item->kode_barang) ? count($item->kode_barang) : 0;
            });
        });
@endphp

<tr class="bg-gray-100 font-semibold">
    <td colspan="2" class="border px-3 py-3 text-center">
        TOTAL SEMUA
    </td>

    <td class="border px-3 py-3 text-center">
        {{ $totalSemua }} Unit
    </td>

    <td colspan="3" class="border px-3 py-3">
        <div class="flex flex-col text-xs gap-1">

            @foreach($perLokasi as $lokasi => $total)
                <span>
                    {{ $lokasi ?? '-' }} : {{ $total }} Unit
                </span>
            @endforeach

        </div>
    </td>
</tr>

@endif
            </tbody>
        </table>

        @elseif($jenis_laporan === 'Peminjaman')

        <table class="w-full text-sm border border-gray-300">
            <thead class="bg-gray-100 text-xs uppercase">
                <tr>
                    <th class="border px-3 py-2">Nama pemakai</th>
                    <th class="border px-3 py-2">Kode barang</th>
                    <th class="border px-3 py-2">Barang</th>
                    <th class="border px-3 py-2">Tgl Pinjam</th>
                    <th class="border px-3 py-2">Tgl Kembali</th>
                    <th class="border px-3 py-2">Kondisi kembali</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporanData as $item)
                <tr>
                    <td class="border px-3 py-2">{{ $item->name }}</td>
                    <td class="border px-3 py-2 font-mono">{{ $item->kode_barang }}</td>
                    <td class="border px-3 py-2">{{ $item->nama_barang }}</td>
                    <td class="border px-3 py-2">
                        {{ $item->tanggal_pinjam ? \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d-m-Y') : '-' }}
                    </td>
                    <td class="border px-3 py-2">
                        {{ $item->tanggal_kembali ? \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') : 'Masih dipakai' }}
                    </td>
                    <td class="border px-3 py-2">{{ $item->kondisi ?? 'belum di cek ' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-400">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
             {{-- ================= SUMMARY SECTION ================= --}}
<tbody>

@php
    $totalDipinjam = collect($laporanData)
        ->filter(fn($item) => empty($item->tanggal_kembali))
        ->count();

    $totalSemuaDipakai = collect($laporanData)->count();

    $kondisiBaik = collect($laporanData)->where('kondisi', 'Baik')->count();
    $rusakRingan = collect($laporanData)->where('kondisi', 'Rusak Ringan')->count();
    $rusakBerat = collect($laporanData)->where('kondisi', 'Rusak Berat')->count();
    $hilang = collect($laporanData)->where('kondisi', 'Hilang')->count();

    $belumCek = collect($laporanData)->filter(function ($item) {
        return $item->kondisi == 'Belum di cek' || $item->kondisi == null;
    })->count();
@endphp

{{-- ================= TOTAL UTAMA ================= --}}
<tr class="bg-gradient-to-r from-blue-50 to-blue-100 font-semibold">
    <td colspan="5" class="border px-4 py-3 text-center text-gray-700">
        Total Masih Dipakai
    </td>
    <td class="border px-4 py-3 text-blue-700 text-center font-bold">
        {{ $totalDipinjam }} Unit
    </td>
</tr>

<tr class="bg-gradient-to-r from-green-50 to-green-100 font-semibold">
    <td colspan="5" class="border px-4 py-3 text-center text-gray-700">
        Total Seluruh Pemakaian
    </td>
    <td class="border px-4 py-3 text-green-700 text-center font-bold">
        {{ $totalSemuaDipakai }} Unit
    </td>
</tr>

{{-- ================= SPACER ================= --}}
<tr>
    <td colspan="6" class="py-2 bg-white border-none"></td>
</tr>

{{-- ================= HEADER KONDISI ================= --}}
<tr class="bg-gray-100">
    <td colspan="6" class="border px-4 py-3 text-center font-semibold text-gray-700 tracking-wide">
        Ringkasan Kondisi Setelah Pemakaian
    </td>
</tr>

{{-- ================= KONDISI GRID STYLE ================= --}}
<tr class="text-sm text-center">

    <td class="border px-3 py-3">
        <div class="text-green-600 font-semibold">Baik</div>
        <div class="text-lg font-bold">{{ $kondisiBaik }}</div>
        <div class="text-xs text-gray-400">Unit</div>
    </td>

    <td class="border px-3 py-3">
        <div class="text-yellow-600 font-semibold">Rusak Ringan</div>
        <div class="text-lg font-bold">{{ $rusakRingan }}</div>
        <div class="text-xs text-gray-400">Unit</div>
    </td>

    <td class="border px-3 py-3">
        <div class="text-red-600 font-semibold">Rusak Berat</div>
        <div class="text-lg font-bold">{{ $rusakBerat }}</div>
        <div class="text-xs text-gray-400">Unit</div>
    </td>

    <td class="border px-3 py-3">
        <div class="text-gray-500 font-semibold">Hilang</div>
        <div class="text-lg font-bold">{{ $hilang }}</div>
        <div class="text-xs text-gray-400">Unit</div>
    </td>

    <td colspan="2" class="border px-3 py-3">
        <div class="text-blue-600 font-semibold">Belum Dicek</div>
        <div class="text-lg font-bold">{{ $belumCek }}</div>
        <div class="text-xs text-gray-400">Unit</div>
    </td>

</tr>

</tbody>

</table>

        @elseif($jenis_laporan === 'Kondisi')

        <table class="w-full text-sm border border-gray-300">
    <thead class="bg-gray-100 text-xs uppercase">
        <tr>
            <th class="border px-3 py-2">No</th>
            <th class="border px-3 py-2">Nama Barang</th>
             <th class="border px-3 py-2">Kode Barang</th>
            <th class="border px-3 py-2">Kondisi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($laporanData as $i => $item)
        <tr>
            <td class="border px-3 py-2">{{ $i+1 }}</td>
            <td class="border px-3 py-2">{{ $item->nama_barang }}</td>
              <td class="border px-3 py-2 font-mono">{{ $item->kode_barang }}</td>

            <td class="border px-3 py-2">
                @if($item->kondisi == 'Baik')
                    <span class="text-green-600">Baik</span>
                @elseif($item->kondisi == 'Rusak Ringan')
                    <span class="text-yellow-600">Rusak Ringan</span>
                @elseif($item->kondisi == 'Rusak Berat')
                    <span class="text-red-600">Rusak Berat</span>
                @elseif($item->kondisi == 'Hilang')
                    <span class="text-gray-500">Hilang</span>
                @endif
            </td>

        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center py-6 text-gray-400">
                Tidak ada data
            </td>
        </tr>
        @endforelse
        {{-- ================= TOTAL ================= --}}
@if(count($laporanData) > 0)
@php
    $totalSemua = count($laporanData);

    $totalBaik = collect($laporanData)->where('kondisi', 'Baik')->count();
    $totalRusakRingan = collect($laporanData)->where('kondisi', 'Rusak Ringan')->count();
    $totalRusakBerat = collect($laporanData)->where('kondisi', 'Rusak Berat')->count();
    $totalHilang = collect($laporanData)->where('kondisi', 'Hilang')->count();
@endphp


<tr class="bg-gray-100 font-semibold text-sm">
    <td colspan="2" class="border px-3 py-3 text-center">
        TOTAL
    </td>

    <td class="border px-3 py-3 text-center">
        {{ $totalSemua }} Unit
    </td>

    <td class="border px-3 py-3">
        <div class="flex flex-col gap-1 text-xs">

            <span class="text-green-600">
                Baik: {{ $totalBaik }}
            </span>

            <span class="text-yellow-600">
                Rusak Ringan: {{ $totalRusakRingan }}
            </span>

            <span class="text-red-600">
                Rusak Berat: {{ $totalRusakBerat }}
            </span>

            <span class="text-gray-500">
                Hilang: {{ $totalHilang }}
            </span>

        </div>
    </td>
</tr>
@endif
    </tbody>
</table>

        @endif

    </div>

    <!-- FOOTER TTD -->
    <div class="mt-10 flex justify-end text-sm">
        <div class="text-center">
            <p>Mengetahui,</p>
            <p class="mt-12">(_____________________)</p>
        </div>
    </div>

</div>
</div>

<style>
@media print {
    button {
        display: none !important;
    }
}
</style>
<style>
@media print {

    body {
        font-family: "Times New Roman", serif;
        font-size: 12px;
        background: white;
    }

    /* Sembunyikan semua kecuali area print */
    body * {
        visibility: hidden;
    }

    #print-area, #print-area * {
        visibility: visible;
    }

    #print-area {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        padding: 20px;
    }

    /* Judul laporan */
    .print-header {
        text-align: center;
        margin-bottom: 20px;
    }

    .print-header h1 {
        font-size: 16px;
        font-weight: bold;
        margin: 0;
    }

    .print-header p {
        font-size: 12px;
        margin: 2px 0;
    }

    /* Table formal */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table, th, td {
        border: 1px solid black;
    }

    th {
        padding: 6px;
        text-align: center;
    }

    td {
        padding: 6px;
    }

    /* Hide button */
    button {
        display: none !important;
    }
}
</style>

<script>
function printPDF() {
    setTimeout(() => {
        window.print();
    }, 300);
}
</script>
</div>
