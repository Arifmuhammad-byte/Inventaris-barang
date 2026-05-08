<div class="p-4 sm:p-6 space-y-6 sm:space-y-8">

{{-- ================= AJUKAN PEMINJAMAN ================= --}}
<div class="w-full">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

{{-- ================= FORM BARANG (KIRI) ================= --}}
<div class="md:col-span-2 mb-6">

<div class="bg-white shadow-sm  p-4 sm:p-6 lg:p-8">

<form wire:submit.prevent="submit" id="formPeminjaman">

{{-- HEADER --}}
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-6">

<h2 class="text-xl font-semibold text-gray-800">
Daftar Barang
</h2>

 <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-3">

  <!-- FILTER -->
<select 
    wire:model.live="filterLokasi"
    class="w-full sm:w-auto
           bg-gray-100
           px-4 py-3 sm:py-2
           text-sm text-gray-700
           rounded-lg
           border border-gray-200
           focus:outline-none
           focus:ring-2 focus:ring-[#09637E]
           transition">

    <option value="">Semua Lokasi</option>
    <option value="Lab Fisika">Lab Fisika</option>
    <option value="Lab Kimia">Lab Kimia</option>
    <option value="Lab Biologi">Lab Biologi</option>
    <option value="Lab Komputer">Lab Komputer</option>
    <option value="Gudang Olahraga">Gudang Olahraga</option>

</select>

<input type="text"
wire:model.live="searchBarang"
placeholder="Cari barang..."
class="w-full sm:w-64 bg-gray-100 px-4 py-2 text-sm rounded-lg
focus:outline-none focus:ring-2 focus:ring-[#09637E]">

</div>
 </div>
{{-- TABLE --}}
<div class="hidden md:block overflow-x-auto">

<table class="min-w-full text-sm text-gray-700">

<thead class="bg-gray-50 text-gray-600 uppercase text-xs">
<tr>
<th class="px-6 py-4 text-left">Pilih</th>
<th class="px-6 py-4 text-left">Foto</th> {{-- TAMBAHAN --}}
<th class="px-6 py-4 text-left">Nama Barang</th>
<th class="px-6 py-4 text-left">Stok</th>
</tr>
</thead>

<tbody class="divide-y divide-gray-100">

@forelse($barangs as $b)

<tr wire:key="barang-{{ $b->id }}"
class="hover:bg-gray-50 transition">

{{-- CHECKBOX --}}
<td class="px-6 py-4">

<input type="checkbox"
wire:change="toggleBarang({{ $b->id }})"
@if(isset($selectedBarang[$b->id])) checked @endif
class="w-4 h-4 text-[#09637E]"
@if($b->kondisi_baik == 0) disabled @endif>

</td>

{{-- FOTO --}}
<td class="px-6 py-4">

@if($b->foto)

<img src="{{ asset('storage/'.$b->foto) }}"
class="w-12 h-12 object-cover rounded-lg shadow-sm border">

@else

{{-- FOTO DEFAULT --}}
<div class="w-12 h-12 bg-gray-200 
flex items-center justify-center 
text-gray-400 text-xs rounded border">

No Img

</div>

@endif

</td>

{{-- NAMA --}}
<td class="px-6 py-4 font-medium">
{{ $b->nama_barang }}
</td>

{{-- STOK --}}
<td class="px-6 py-4">

<span class="px-3 py-1 text-xs font-medium
{{ $b->kondisi_baik > 0
? 'bg-green-100 text-green-600'
: 'bg-red-100 text-red-600' }}">

{{ $b->kondisi_baik }} tersedia

</span>

</td>

</tr>

@empty

<tr>
<td colspan="4"
class="text-center py-8 text-gray-400">

Tidak ada barang tersedia

</td>
</tr>

@endforelse

</tbody>

</table>


</div>

<div class="md:hidden space-y-4">

@forelse($barangs as $b)

<div class="bg-gray-50 rounded-xl p-4 shadow-sm space-y-4">

    <!-- TOP -->
    <div class="flex items-start gap-4">

        <!-- CHECKBOX -->
        <div class="pt-1">

            <input type="checkbox"
            wire:change="toggleBarang({{ $b->id }})"
            @if(isset($selectedBarang[$b->id])) checked @endif
            class="w-4 h-4 text-[#09637E]"
            @if($b->kondisi_baik == 0) disabled @endif>

        </div>

        <!-- FOTO -->
        <div>

            @if($b->foto)

                <img src="{{ asset('storage/'.$b->foto) }}"
                class="w-16 h-16 object-cover rounded-lg border shadow-sm">

            @else

                <div class="w-16 h-16 bg-gray-200 flex items-center justify-center text-gray-400 text-xs rounded-lg border">

                    No Img

                </div>

            @endif

        </div>

        <!-- INFO -->
        <div class="flex-1 min-w-0">

            <h3 class="font-semibold text-gray-800 break-words">
                {{ $b->nama_barang }}
            </h3>

            <div class="mt-2">

                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full
                {{ $b->kondisi_baik > 0
                ? 'bg-green-100 text-green-600'
                : 'bg-red-100 text-red-600' }}">

                    {{ $b->kondisi_baik }} tersedia

                </span>

            </div>

        </div>

    </div>

</div>

@empty

<div class="text-center py-10 text-gray-400 text-sm">
    Tidak ada barang tersedia
</div>

@endforelse

</div>

</form>

{{-- PAGINATION --}}
<div class="mt-4 sm:mt-6 overflow-x-auto">
{{ $barangs->links('vendor.pagination.tailwind') }}
</div>

</div>

</div>


{{-- ================= KERANJANG (KANAN) ================= --}}
<div class="lg:col-span-1">

<div class="bg-white shadow-sm  p-4 sm:p-6 lg:sticky lg:top-24">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">

        <h3 class="text-base sm:text-lg font-semibold text-gray-800">
            Keranjang
        </h3>

        <span class="text-xs bg-[#09637E] text-white px-2 py-1 rounded-full">

            {{ count($selectedBarang) }}

        </span>

    </div>

    {{-- LIST --}}
    <div class="space-y-3 max-h-[350px] sm:max-h-[400px] overflow-y-auto pr-1">

        @if(count($selectedBarang) > 0)

            @php
                $totalUnit = 0;
            @endphp

            @foreach($selectedBarang as $id => $item)

                @php
                    $totalUnit += $item['jumlah'];
                @endphp

                <div class="bg-gray-50 rounded-xl p-4 space-y-3">

                    {{-- TOP --}}
                    <div class="flex justify-between gap-3">

                        <div class="flex-1 min-w-0">

                            {{-- NAMA --}}
                            <p class="text-sm font-medium text-gray-700 break-words">
                                {{ $item['nama'] }}
                            </p>

                            {{-- STOK --}}
                            <p class="text-xs text-gray-400 mt-1">
                                Stok tersedia: {{ $item['stok'] }}
                            </p>

                        </div>

                        {{-- HAPUS --}}
                        <button
                        wire:click="removeBarang({{ $id }})"
                        class="text-red-500 text-xs hover:underline whitespace-nowrap">

                            Hapus

                        </button>

                    </div>

                    {{-- CONTROL --}}
                    <div class="flex items-center justify-between">

                        <p class="text-xs text-gray-400">
                            Jumlah
                        </p>

                        <div class="flex items-center gap-2">

                            {{-- MINUS --}}
                            <button
                            wire:click="kurangiJumlah({{ $id }})"
                            class="w-8 h-8 flex items-center justify-center
                                   bg-gray-200 hover:bg-gray-300
                                   rounded-lg text-sm transition">

                                −

                            </button>

                            {{-- JUMLAH --}}
                            <span class="text-sm font-semibold w-8 text-center">

                                {{ $item['jumlah'] }}

                            </span>

                            {{-- PLUS --}}
                            <button
                            wire:click="tambahJumlah({{ $id }})"
                            class="w-8 h-8 flex items-center justify-center
                                   bg-gray-200 hover:bg-gray-300
                                   rounded-lg text-sm transition">

                                +

                            </button>

                        </div>

                    </div>

                </div>

            @endforeach

        @else

            <div class="text-center py-8 text-sm text-gray-400">
                Belum ada barang dipilih
            </div>

        @endif

    </div>

    {{-- TOTAL --}}
    @if(count($selectedBarang) > 0)

    <div class="mt-4 border-t pt-4 text-sm text-gray-600 space-y-2">

        <div class="flex justify-between">

            <span>Total Item</span>

            <span class="font-semibold">
                {{ count($selectedBarang) }}
            </span>

        </div>

        <div class="flex justify-between">

            <span>Total Unit</span>

            <span class="font-semibold text-[#09637E]">
                {{ collect($selectedBarang)->sum('jumlah') }}
            </span>

        </div>

    </div>

    @endif

    {{-- BUTTON --}}
    <div class="mt-6">

        <button type="submit"
        form="formPeminjaman"
        wire:loading.attr="disabled"
        wire:target="submit"

        class="w-full bg-[#09637E] text-white py-3
               hover:bg-[#088395] transition 
               flex items-center justify-center gap-2
               text-sm font-medium">

            {{-- SPINNER --}}
            <svg wire:loading wire:target="submit"
            class="animate-spin h-5 w-5 text-white"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24">

                <circle class="opacity-25"
                cx="12" cy="12" r="10"
                stroke="currentColor"
                stroke-width="4"></circle>

                <path class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v8H4z"></path>

            </svg>

            {{-- TEXT --}}
            <span wire:loading.remove wire:target="submit">
                Ajukan Pemakaian
            </span>

            <span wire:loading wire:target="submit">
                Memproses...
            </span>

        </button>

    </div>

</div>

</div>
</div>

 {{-- ================= RIWAYAT PEMINJAMAN ================= --}}
<div class="bg-white shadow-sm  p-4 sm:p-6 space-y-4 sm:space-y-6">

    <!-- HEADER -->
   <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800">
            Status & Riwayat Pengajuan
        </h2>
    </div>

    <!-- TABLE -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm border-separate border-spacing-y-2">

            <!-- HEAD -->
            <thead>
                <tr class="text-gray-500 text-xs uppercase">
                    <th class="px-6 py-2 text-left">No</th>
                    <th class="px-6 py-2 text-left">Tanggal</th>
                    <th class="px-6 py-2 text-left">Kode Barang</th>
                    <th class="px-6 py-2 text-left">Barang</th>
                    <th class="px-6 py-2 text-left">Jumlah</th>
                    <th class="px-6 py-2 text-left">Status</th>
                    <th class="px-6 py-2 text-right">Aksi</th>
                </tr>
            </thead>

            <!-- BODY -->
            <tbody>

            @forelse($riwayat as $r)

                <tr class="bg-gray-50 hover:bg-gray-100 transition rounded-xl">

                    {{-- NO --}}
                    <td class="px-6 py-4 font-medium text-gray-700">
                        {{ $loop->iteration }}
                    </td>

                    {{-- TANGGAL --}}
                    <td class="px-6 py-4 text-gray-600">
                        {{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d M Y') }}
                    </td>

                  <td class="px-6 py-4">
    @foreach($r->detailBarang->groupBy('barang_id') as $barangId => $details)
        <div class="space-y-1 mb-2">

            @foreach($details as $d)
                @if($d->barangUnit)
                    <div class="font-mono text-gray-700">
                        {{ $d->barangUnit->kode_barang }}
                    </div>
                @endif
            @endforeach

        </div>
    @endforeach
</td>

                    {{-- BARANG --}}
                    <td class="px-6 py-4">
    @foreach($r->detailBarang->groupBy('barang_id') as $barangId => $details)
        <div class="font-medium text-gray-700 mb-2">
            {{ $details->first()->barang->nama_barang ?? '-' }}
        </div>
    @endforeach
</td>

                    {{-- JUMLAH --}}
                    <td class="px-6 py-4">
    @foreach($r->detailBarang->groupBy('barang_id') as $barangId => $details)
        <div class="text-gray-600 mb-2">
            {{ $details->count() }} unit
        </div>
    @endforeach
</td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'Menunggu' => 'bg-yellow-100 text-yellow-700',
                                'Pending Cek' => 'bg-yellow-100 text-yellow-700',
                                'Disetujui' => 'bg-green-100 text-green-700',
                                'Ditolak' => 'bg-red-100 text-red-700',
                                'Dikembalikan' => 'bg-blue-100 text-blue-700',
                                'Dibatalkan' => 'bg-gray-200 text-gray-600',
                            ];
                        @endphp

                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$r->status] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ $r->status }}
                        </span>
                    </td>

             {{-- AKSI --}}
<td class="px-6 py-4">

<div class="flex justify-end">

@if($r->status === 'Menunggu')

<button 
    wire:click="batalkan({{ $r->id }})"
    wire:loading.attr="disabled"
    wire:target="batalkan({{ $r->id }})"

    class="relative flex items-center justify-center
           w-[120px] h-[38px]
           text-xs font-semibold
           bg-red-600 text-white
           rounded-lg
           hover:bg-red-700
           active:scale-[0.97]
           transition-all duration-200
           disabled:opacity-60">

    {{-- Spinner --}}
    <svg 
        wire:loading 
        wire:target="batalkan({{ $r->id }})"
        class="absolute animate-spin h-4 w-4"
        xmlns="http://www.w3.org/2000/svg" 
        fill="none" 
        viewBox="0 0 24 24">

        <circle 
            class="opacity-25"
            cx="12" cy="12" r="10"
            stroke="currentColor"
            stroke-width="4">
        </circle>

        <path 
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v8H4z">
        </path>

    </svg>

    {{-- Text --}}
    <span 
        wire:loading.class="opacity-0"
        wire:target="batalkan({{ $r->id }})">

        Batalkan

    </span>

</button>

@elseif($r->status === 'Dibatalkan')

<button 
    wire:click="hapus({{ $r->id }})"
    wire:loading.attr="disabled"
    wire:target="hapus({{ $r->id }})"

    class="relative flex items-center justify-center
           w-[120px] h-[38px]
           text-xs font-semibold
           bg-gray-700 text-white
           rounded-lg
           hover:bg-gray-800
           active:scale-[0.97]
           transition-all duration-200
           disabled:opacity-60">

    {{-- Spinner --}}
    <svg 
        wire:loading 
        wire:target="hapus({{ $r->id }})"
        class="absolute animate-spin h-4 w-4"
        xmlns="http://www.w3.org/2000/svg" 
        fill="none" 
        viewBox="0 0 24 24">

        <circle 
            class="opacity-25"
            cx="12" cy="12" r="10"
            stroke="currentColor"
            stroke-width="4">
        </circle>

        <path 
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v8H4z">
        </path>

    </svg>

    {{-- Text --}}
    <span 
        wire:loading.class="opacity-0"
        wire:target="hapus({{ $r->id }})">

        Hapus

    </span>

</button>

@else

<span class="w-[120px] text-center text-xs text-gray-400">
-
</span>

@endif

</div>

</td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <span class="text-3xl">📦</span>
                            <span>Belum ada pengajuan pemakaian</span>
                        </div>
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>
    </div>
    <!-- MOBILE VIEW -->
<div class="md:hidden space-y-4">

@forelse($riwayat as $r)

<div class="bg-gray-50 rounded-xl p-4 shadow-sm space-y-4">

    <!-- TOP -->
    <div class="flex items-start justify-between gap-3">

        <div>
            <p class="text-xs text-gray-400">
                {{ \Carbon\Carbon::parse($r->tanggal_pinjam)->format('d M Y') }}
            </p>

            <h3 class="text-sm font-semibold text-gray-800 mt-1">
                Pengajuan #{{ $loop->iteration }}
            </h3>
        </div>

        {{-- STATUS --}}
        @php
            $statusColors = [
                'Menunggu' => 'bg-yellow-100 text-yellow-700',
                'Pending Cek' => 'bg-yellow-100 text-yellow-700',
                'Disetujui' => 'bg-green-100 text-green-700',
                'Ditolak' => 'bg-red-100 text-red-700',
                'Dikembalikan' => 'bg-blue-100 text-blue-700',
                'Dibatalkan' => 'bg-gray-200 text-gray-600',
            ];
        @endphp

        <span class="px-3 py-1 text-[11px] font-medium rounded-full whitespace-nowrap
        {{ $statusColors[$r->status] ?? 'bg-gray-100 text-gray-500' }}">

            {{ $r->status }}

        </span>

    </div>

    <!-- LIST BARANG -->
    <div class="space-y-3">

        @foreach($r->detailBarang->groupBy('barang_id') as $barangId => $details)

        <div class="bg-white rounded-lg p-3 border border-gray-100">

            <p class="text-sm font-medium text-gray-800">
                {{ $details->first()->barang->nama_barang ?? '-' }}
            </p>

            <div class="mt-2 space-y-1">

                <div class="text-xs text-gray-500">
                    Jumlah: {{ $details->count() }} unit
                </div>

                <div class="text-xs text-gray-500">
                    Kode:
                </div>

                <div class="space-y-1">

                    @foreach($details as $d)

                        @if($d->barangUnit)

                        <div class="font-mono text-xs text-gray-700 break-all">
                            {{ $d->barangUnit->kode_barang }}
                        </div>

                        @endif

                    @endforeach

                </div>

            </div>

        </div>

        @endforeach

    </div>

    <!-- AKSI -->
    <div>

        @if($r->status === 'Menunggu')

        <button 
        wire:click="batalkan({{ $r->id }})"
        wire:loading.attr="disabled"
        wire:target="batalkan({{ $r->id }})"

        class="relative w-full h-10
               flex items-center justify-center
               text-xs font-semibold
               bg-red-600 text-white
               rounded-lg hover:bg-red-700
               transition-all duration-200">

            {{-- Spinner --}}
            <svg 
            wire:loading 
            wire:target="batalkan({{ $r->id }})"
            class="absolute animate-spin h-4 w-4"
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24">

                <circle class="opacity-25"
                cx="12" cy="12" r="10"
                stroke="currentColor"
                stroke-width="4"></circle>

                <path class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v8H4z"></path>

            </svg>

            <span wire:loading.class="opacity-0"
            wire:target="batalkan({{ $r->id }})">

                Batalkan

            </span>

        </button>

        @elseif($r->status === 'Dibatalkan')

        <button 
        wire:click="hapus({{ $r->id }})"
        wire:loading.attr="disabled"
        wire:target="hapus({{ $r->id }})"

        class="relative w-full h-10
               flex items-center justify-center
               text-xs font-semibold
               bg-gray-700 text-white
               rounded-lg hover:bg-gray-800
               transition-all duration-200">

            {{-- Spinner --}}
            <svg 
            wire:loading 
            wire:target="hapus({{ $r->id }})"
            class="absolute animate-spin h-4 w-4"
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24">

                <circle class="opacity-25"
                cx="12" cy="12" r="10"
                stroke="currentColor"
                stroke-width="4"></circle>

                <path class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8v8H4z"></path>

            </svg>

            <span wire:loading.class="opacity-0"
            wire:target="hapus({{ $r->id }})">

                Hapus

            </span>

        </button>

        @endif

    </div>

</div>

@empty

<div class="text-center py-10 text-gray-400">

    <div class="flex flex-col items-center gap-2">
        <span class="text-3xl">📦</span>
        <span class="text-sm">Belum ada pengajuan pemakaian</span>
    </div>

</div>

@endforelse

</div>

</div>
