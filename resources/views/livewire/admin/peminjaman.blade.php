<div class="p-4 bg-gray-50 min-h-screen">

    <div class="bg-white shadow-sm mx-2 lg:mx-4">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Data Pemakaian Barang
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola dan ubah status pemakaian
                </p>
            </div>

            {{-- Search --}}
            <div class="relative w-72">
                <input type="text"
                       wire:model.live.debounce.500ms="search"
                       placeholder="Cari..."
                       class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg
                              focus:outline-none focus:ring-2 focus:ring-[#09637E]">

                <div wire:loading wire:target="search"
                     class="absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-4 w-4 text-[#09637E]"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    </svg>
                </div>
            </div>

        </div>

      {{-- TABLE --}}
<div class="overflow-x-auto">
    <table class="w-full text-sm text-gray-700">

        <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">No</th>
                <th class="px-6 py-3 text-left">Pemakai</th>
                <th class="px-6 py-3 text-left">Kode Barang</th>
                <th class="px-6 py-3 text-left">Barang</th>
                <th class="px-6 py-3 text-left">Jumlah</th>
                <th class="px-6 py-3 text-left">Tanggal</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Aksi</th>
                <th class="px-6 py-3 text-left">Struk</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">

        @forelse($peminjamans as $index => $peminjaman)

            @php
                $grouped = $peminjaman->detailBarang->groupBy('barang_id');
            @endphp

            <tr class="hover:bg-gray-50">

                {{-- NO --}}
                <td class="px-6 py-3">
                    {{ $peminjamans->firstItem() + $index }}
                </td>

                {{-- PEMINJAM --}}
                <td class="px-6 py-3 font-medium">
                    {{ $peminjaman->user->name ?? '-' }}
                </td>

                {{-- KODE BARANG --}}
                <td class="px-6 py-3">
                    <div class="space-y-1">
                        @foreach($grouped as $details)
                            @foreach($details as $d)
                                @if($d->barangUnit)
                                    <div class="font-mono text-gray-700">
                                        {{ $d->barangUnit->kode_barang }}
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </td>

                {{-- NAMA BARANG (NO DUPLICATE) --}}
                <td class="px-6 py-3">
                    <div class="space-y-1">
                        @foreach($grouped as $details)
                            <div class="font-medium">
                                {{ $details->first()->barang->nama_barang ?? '-' }}
                            </div>
                        @endforeach
                    </div>
                </td>

                {{-- JUMLAH --}}
                <td class="px-6 py-3">
                    <div class="space-y-1">
                        @foreach($grouped as $details)
                            <div>
                                <span class="bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded">
                                    x{{ $details->count() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </td>

                {{-- TANGGAL --}}
                <td class="px-6 py-3">
                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                </td>

                {{-- STATUS --}}
                <td class="px-6 py-3">
                    @php
                        $statusColors = [
                            'Menunggu' => 'bg-yellow-100 text-yellow-700',
                            'Pending Cek' => 'bg-yellow-100 text-yellow-700',
                            'Disetujui' => 'bg-blue-100 text-blue-700',
                            'Ditolak' => 'bg-red-100 text-red-700',
                            'Dikembalikan' => 'bg-green-100 text-green-700',
                            'Dibatalkan' => 'bg-gray-200 text-gray-600',
                        ];
                    @endphp

                    <span class="px-2 py-1 text-xs font-semibold rounded
                        {{ $statusColors[$peminjaman->status] ?? 'bg-gray-100 text-gray-500' }}">
                        {{ $peminjaman->status }}
                    </span>
                </td>

                {{-- AKSI --}}
                <td class="px-6 py-3">
                    <select 
                        wire:change="ubahStatus({{ $peminjaman->id }}, $event.target.value)"
                        @if(in_array($peminjaman->status, ['Dikembalikan','Dibatalkan'])) disabled @endif
                        class="px-2 py-1 text-sm rounded
                            {{ in_array($peminjaman->status, ['Dikembalikan','Dibatalkan']) 
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
                                : '' }}">
                        <option value="">Ubah</option>
                        <option value="Disetujui">Setujui</option>
                        <option value="Ditolak">Tolak</option>
                    </select>
                </td>

                
 {{-- STRUK --}}
<td class="px-6 py-3">
    @if($peminjaman->status === 'Disetujui')
        <button 
            wire:click="showStruk({{ $peminjaman->id }})"
            wire:loading.attr="disabled"
            wire:target="showStruk({{ $peminjaman->id }})"

            class="relative flex items-center justify-center gap-2
                   px-4 py-2 text-sm font-medium
                   bg-[#088395] text-white
                   hover:bg-[#07707a]
                   transition duration-200
                   disabled:opacity-50
                   min-w-[90px]">

            {{-- Spinner --}}
            <svg 
                wire:loading 
                wire:target="showStruk({{ $peminjaman->id }})"
                class="animate-spin h-4 w-4"
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

            {{-- Text Normal --}}
            <span 
                wire:loading.remove 
                wire:target="showStruk({{ $peminjaman->id }})">
                Cetak
            </span>

        </button>
    @else
        <span class="text-gray-400 text-xs">-</span>
    @endif
</td>

            </tr>

        @empty

            <tr>
                <td colspan="9" class="text-center py-8 text-gray-400">
                    Belum ada data
                </td>
            </tr>

        @endforelse

        </tbody>

    </table>
</div>

        {{-- Pagination --}}
        <div class="px-6 py-4">
            {{ $peminjamans->links() }}
        </div>

    </div>

    @if($showStrukModal)
<div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white w-[320px] p-6 shadow-lg">

        <!-- STRUK -->
        <div id="area-print" class="text-sm text-gray-800">

            <!-- HEADER -->
            <div class="text-center border-b border-dashed pb-3 mb-3">
                <h2 class="font-bold text-lg tracking-wide">
                    STRUK PEMINJAMAN
                </h2>
                <p class="text-xs text-gray-500">
                    Sistem Inventaris
                </p>
            </div>

            <!-- INFO -->
            <div class="space-y-1 mb-3">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-medium">
                        {{ $selectedPeminjaman->user->name }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Tanggal</span>
                    <span>
                        {{ \Carbon\Carbon::parse($selectedPeminjaman->tanggal_pinjam)->format('d M Y') }}
                    </span>
                </div>
            </div>

            <!-- GARIS -->
            <div class="border-b border-dashed mb-3"></div>

            <!-- DETAIL BARANG -->
            <!-- DETAIL BARANG -->
<div class="mb-3">

<p class="text-xs text-gray-500 mb-2">
Detail Barang
</p>

<div class="space-y-2 text-xs">

@foreach($selectedPeminjaman->detailBarang->groupBy('barang_id') as $barangId => $items)

    @php
        $first = $items->first();
        $jumlah = $items->count();
    @endphp

    <!-- NAMA + JUMLAH -->
    <div>

        <div class="flex justify-between font-medium">

            <span>
                {{ $first->barang->nama_barang }}
                ({{ $jumlah }} unit)
            </span>

        </div>

        <!-- LIST KODE -->
        <div class="ml-3 mt-1 space-y-0.5">

            @foreach($items as $d)

                <div class="flex justify-between">

                    <span class="text-gray-400">
                        -
                    </span>

                    <span class="font-mono text-gray-700">
                        {{ $d->barangUnit->kode_barang ?? '-' }}
                    </span>

                </div>

            @endforeach

        </div>

    </div>

@endforeach

</div>

</div>
            <!-- GARIS -->
            <div class="border-b border-dashed my-3"></div>

            <!-- FOOTER -->
            <div class="text-center text-xs text-gray-400 mt-4">
                Terima kasih 
            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end gap-2 mt-5">
         <button wire:click.prevent="$set('showStrukModal', false)"
        class="px-3 py-1 text-sm bg-gray-200 hover:bg-gray-300 transition">
    Batal
</button>


           <button onclick="window.print()"
        class="px-3 py-1 text-sm bg-[#088395] text-white">
    Cetak
</button>
        </div>

    </div>
</div>
@endif
<div>

<style>
@media print {

    body * {
        visibility: hidden;
    }

    #area-print, #area-print * {
        visibility: visible;
    }

    #area-print {
        position: absolute;
        left: 0;
        top: 0;
        width: 72mm; /* pas thermal 80mm */
        font-family: monospace;
        font-size: 11px;
        line-height: 1.4;
    }

    @page {
        size: 80mm auto;
        margin: 3mm;
    }
}
</style>