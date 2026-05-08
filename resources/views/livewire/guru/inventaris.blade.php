<div class="p-4 sm:p-6 lg:p-8 bg-gray-50 min-h-screen">

    <div class="bg-white shadow-sm p-4 sm:p-6 lg:p-8 space-y-4 sm:space-y-6 lg:space-y-8 rounded-xl">

       {{-- HEADER --}}
<div class="flex flex-col gap-4 sm:gap-6">

    <!-- TITLE -->
    <div>
        <h1 class="text-lg sm:text-2xl font-semibold text-gray-800 tracking-tight">
            Inventaris Barang
        </h1>

        <p class="text-xs sm:text-sm text-gray-400 mt-1">
            Daftar seluruh barang inventaris sekolah
        </p>
    </div>


    {{-- FILTER + SEARCH --}}
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full">

        {{-- FILTER --}}
        <select wire:model.live="filterKategori"
            class="w-full sm:w-auto
                   bg-gray-100 px-3 sm:px-4 py-2 text-xs sm:text-sm 
                   rounded-lg
                   focus:outline-none focus:ring-2 focus:ring-[#09637E]">

            <option value="">Semua Kategori</option>
            <option value="Barang Laboratorium">Barang Laboratorium</option>
            <option value="Alat Olahraga">Alat Olahraga</option>
            <option value="Lainnya">Lainnya</option>

        </select>


        {{-- SEARCH --}}
        <div class="relative w-full sm:w-64">

            <input type="text"
                wire:model.live="search"
                placeholder="Cari barang..."
                class="w-full bg-gray-100 px-3 sm:px-4 py-2 text-xs sm:text-sm 
                       rounded-lg
                       focus:outline-none focus:ring-2 focus:ring-[#09637E]">

            {{-- LOADING --}}
            <div wire:loading wire:target="search"
                class="absolute right-3 top-2.5">

                <svg class="animate-spin h-4 w-4 text-[#09637E]"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">

                    <circle class="opacity-25"
                        cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>

                    <path class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v8H4z"></path>

                </svg>

            </div>

        </div>

    </div>

</div>
{{-- ================= MOBILE CARD ================= --}}
<div class="md:hidden space-y-3 mt-4">

@forelse($barang as $b)

<div class="bg-white p-4 shadow-sm">

    <div class="flex gap-3">

        {{-- FOTO --}}
        @if($b->foto)
            <img src="{{ asset('storage/'.$b->foto) }}"
                 class="w-14 h-14 object-cover rounded">
        @else
            <div class="w-14 h-14 flex items-center justify-center 
                        bg-gray-100 text-gray-400 text-xs rounded">
                No Img
            </div>
        @endif

        {{-- INFO --}}
        <div class="flex-1">

            <h3 class="text-sm font-semibold text-gray-800">
                {{ $b->nama_barang }}
            </h3>

            <p class="text-xs text-gray-500">
                {{ $b->kategori }}
            </p>

            <span class="inline-block mt-1 px-2 py-1 text-xs rounded
                @if($b->lokasi == 'Lab Fisika') bg-blue-50 text-blue-600
                @elseif($b->lokasi == 'Lab Kimia') bg-purple-50 text-purple-600
                @elseif($b->lokasi == 'Lab Biologi') bg-green-50 text-green-600
                @elseif($b->lokasi == 'Lab Komputer') bg-emerald-50 text-emerald-600
                @elseif($b->lokasi == 'Gudang Olahraga') bg-red-50 text-yellow-600
                @else bg-gray-100 text-gray-500
                @endif">

                {{ $b->lokasi }}

            </span>

        </div>

    </div>

    {{-- BUTTON --}}
    <div class="mt-3">

        <button 
            wire:click="showDetail({{ $b->id }})"
            wire:loading.attr="disabled"
            wire:target="showDetail({{ $b->id }})"

            class="w-full bg-[#09637E] text-white text-xs py-2">

            <span wire:loading.class="opacity-0"
                  wire:target="showDetail({{ $b->id }})">
                Detail
            </span>

            <svg wire:loading
                 wire:target="showDetail({{ $b->id }})"
                 class="animate-spin h-4 w-4 mx-auto absolute"
                 xmlns="http://www.w3.org/2000/svg" 
                 fill="none" 
                 viewBox="0 0 24 24">

                <circle class="opacity-25"
                        cx="12" cy="12" r="10"
                        stroke="white"
                        stroke-width="4"></circle>

                <path class="opacity-90"
                      fill="white"
                      d="M4 12a8 8 0 018-8v8H4z"></path>

            </svg>

        </button>

    </div>

</div>

@empty
<div class="text-center text-gray-400 py-6">
    Data belum tersedia
</div>
@endforelse

</div>
{{-- ================= MOBILE PAGINATION ================= --}}
<div class="md:hidden flex justify-between items-center mt-6 gap-2">

    {{-- PREV --}}
    @if ($barang->onFirstPage())
        <span class="px-4 py-2 text-xs bg-gray-200 text-gray-400 rounded-lg">
            Prev
        </span>
    @else
        <button 
            wire:click="previousPage"
            class="px-4 py-2 text-xs bg-[#09637E] text-white rounded-lg">
            Prev
        </button>
    @endif


    {{-- INFO --}}
    <span class="text-xs text-gray-500">
        {{ $barang->currentPage() }} / {{ $barang->lastPage() }}
    </span>


    {{-- NEXT --}}
    @if ($barang->hasMorePages())
        <button 
            wire:click="nextPage"
            class="px-4 py-2 text-xs bg-[#09637E] text-white rounded-lg">
            Next
        </button>
    @else
        <span class="px-4 py-2 text-xs bg-gray-200 text-gray-400 rounded-lg">
            Next
        </span>
    @endif

</div>
        <div class="hidden md:block">
        <div class="overflow-x-auto">
    <table class="min-w-full text-sm text-gray-700">

        <thead class="text-xs uppercase text-gray-400 border-b">
            <tr>
                <th class="px-6 py-4 text-left">No</th>
                   <th class="px-6 py-4 text-left">Foto</th>
                <th class="px-6 py-4 text-left">Nama Barang</th>
                <th class="px-6 py-4 text-left">Kategori</th>
                <th class="px-6 py-4 text-left">Lokasi</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
@forelse($barang as $index => $b)

<tr class="hover:bg-gray-50 transition">

    {{-- NO --}}
    <td class="px-6 py-4 text-gray-400">
        {{ $barang->firstItem() + $index }}
    </td>

    {{-- FOTO --}}
    <td class="px-6 py-4">
        @if($b->foto)
            <img src="{{ asset('storage/'.$b->foto) }}"
                 class="w-12 h-12 object-cover rounded border">
        @else
            <div class="w-12 h-12 flex items-center justify-center 
                        bg-gray-100 text-gray-400 text-xs border rounded">
                No Img
            </div>
        @endif
    </td>

    {{-- NAMA --}}
    <td class="px-6 py-4 font-medium text-gray-900">
        {{ $b->nama_barang }}
    </td>

    {{-- KATEGORI --}}
    <td class="px-6 py-4 text-gray-500">
        {{ $b->kategori }}
    </td>

    {{-- LOKASI --}}
    <td class="px-6 py-4">
        <span class="px-3 py-1 text-xs font-medium
        @if($b->lokasi == 'Lab Fisika') 
            bg-blue-50 text-blue-600
        @elseif($b->lokasi == 'Lab Kimia') 
            bg-purple-50 text-purple-600
        @elseif($b->lokasi == 'Lab Biologi') 
            bg-green-50 text-green-600
        @elseif($b->lokasi == 'Lab Komputer') 
            bg-emerald-50 text-emerald-600
        @elseif($b->lokasi == 'Gudang Olahraga') 
            bg-red-50 text-yellow-600
        @else 
            bg-gray-100 text-gray-500
        @endif">

            {{ $b->lokasi }}

        </span>
    </td>

    {{-- AKSI --}}
    <td class="px-6 py-4 text-center">

        <button 
    wire:click="showDetail({{ $b->id }})"
    wire:loading.attr="disabled"
    wire:target="showDetail({{ $b->id }})"

    class="relative inline-flex items-center justify-center
           bg-[#09637E] text-white text-xs font-medium
           px-4 py-2
           hover:bg-[#0b7a99]
           transition duration-200
           min-w-[90px]">

    {{-- TEXT NORMAL --}}
    <span 
        wire:loading.class="opacity-0"
        wire:target="showDetail({{ $b->id }})">

        Detail

    </span>

    {{-- SPINNER (TIDAK MENGUBAH UKURAN) --}}
    <svg 
        wire:loading
        wire:target="showDetail({{ $b->id }})"

        class="animate-spin h-4 w-4 absolute"
        xmlns="http://www.w3.org/2000/svg" 
        fill="none" 
        viewBox="0 0 24 24">

        <circle 
            class="opacity-25"
            cx="12" cy="12" r="10"
            stroke="white"
            stroke-width="4">
        </circle>

        <path 
            class="opacity-90"
            fill="white"
            d="M4 12a8 8 0 018-8v8H4z">
        </path>

    </svg>

</button>

    </td>

</tr>

@empty

<tr>
<td colspan="6"
    class="text-center py-10 text-gray-400">

    Data belum tersedia

</td>
</tr>

@endforelse
</tbody>

    </table>

    <div class="mt-6">
    {{ $barang->links('vendor.pagination.tailwind') }}
</div>

</div>
</div>

@if($showModal)

<div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-3 sm:px-4">

    <div class="bg-white w-full max-w-md 
                p-4 sm:p-6 lg:p-8 
                shadow-lg 
                max-h-[90vh] overflow-y-auto">

        <!-- HEADER -->
        <div class="flex justify-between items-center mb-4 sm:mb-6">

            <h2 class="text-base sm:text-lg font-semibold text-gray-800">
                Detail Barang
            </h2>

            <button wire:click="closeModal"
                class="text-gray-400 hover:text-gray-700 text-lg">
                ✕
            </button>

        </div>


        <!-- CONTENT -->
        <div class="space-y-3 sm:space-y-4 text-xs sm:text-sm">

            {{-- FOTO --}}
            <div class="flex justify-center mb-2 sm:mb-3">

                @if($selectedBarang->foto)

                    <img 
                        src="{{ asset('storage/'.$selectedBarang->foto) }}"
                        class="w-16 h-16 sm:w-20 sm:h-20 object-cover rounded-lg border shadow-sm">

                @else

                    <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center 
                                bg-gray-100 text-gray-400 text-xs 
                                border rounded-lg">

                        No Img

                    </div>

                @endif

            </div>


            <!-- DATA -->
            <div>
                <p class="text-gray-400 text-[11px] sm:text-xs">Nama Barang</p>
                <p class="font-medium text-gray-800 break-words">
                    {{ $selectedBarang->nama_barang ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-400 text-[11px] sm:text-xs">Kategori</p>
                <p class="font-medium text-gray-800">
                    {{ $selectedBarang->kategori ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-400 text-[11px] sm:text-xs">Lokasi</p>
                <p class="font-medium text-gray-800">
                    {{ $selectedBarang->lokasi ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-400 text-[11px] sm:text-xs">Keterangan</p>
                <p class="font-medium text-gray-800 break-words">
                    {{ $selectedBarang->keterangan ?? '-' }}
                </p>
            </div>

        </div>


        <!-- FOOTER -->
        <div class="mt-5 sm:mt-8">

            <button wire:click="closeModal"
                class="w-full sm:w-auto
                       bg-[#09637E] text-white 
                       px-5 py-2 text-xs sm:text-sm 
                       rounded-lg
                       hover:opacity-90 transition">

                Tutup

            </button>

        </div>

    </div>

</div>

@endif