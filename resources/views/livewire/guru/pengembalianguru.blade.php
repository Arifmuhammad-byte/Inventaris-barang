<div class="mt-6 sm:mt-8">

    {{-- Alert --}}
    @if(session()->has('message'))
        <div class="mb-4 sm:mb-6 px-3 sm:px-4 py-2.5 sm:py-3 
                    bg-emerald-50 text-emerald-700 
                    text-xs sm:text-sm 
                    border-l-4 border-emerald-500 
                    rounded-lg flex items-start gap-2">

            <!-- ICON -->
            <div class="mt-[2px]">
                ✅
            </div>

            <!-- TEXT -->
            <div class="flex-1 break-words">
                {{ session('message') }}
            </div>

        </div>
    @endif

    <div class="bg-white shadow-sm p-4 sm:p-6 space-y-4 sm:space-y-6 ">

        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                Form Pengembalian
            </h2>
            <p class="text-sm text-gray-500">
                Daftar riwayat peminjaman barang anda
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm text-left text-gray-700">

                <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                   
                </thead>

           <tbody>

@forelse($riwayat as $peminjaman)

<tr>
    <td colspan="6" class="px-4 py-6">

        <div class="bg-white p-6 shadow-sm hover:shadow-md transition duration-200 space-y-5">

            <!-- HEADER -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">

                <div>
                    <p class="text-xs text-gray-400">
                        {{ date('d M Y', strtotime($peminjaman->tanggal_pinjam)) }}
                    </p>
                    <p class="text-base font-semibold text-gray-800">
                        Peminjaman #{{ $peminjaman->id }}
                    </p>
                </div>

                <!-- STATUS -->
                <div>
                    @switch($peminjaman->status)

                        @case('Disetujui')
                            <span class="px-3 py-1 text-xs rounded-full bg-emerald-50 text-emerald-600">
                                Dipinjam
                            </span>
                            @break

                        @case('Menunggu')
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-50 text-yellow-600">
                                Menunggu
                            </span>
                            @break

                        @case('Ditolak')
                            <span class="px-3 py-1 text-xs rounded-full bg-red-50 text-red-600">
                                Ditolak
                            </span>
                            @break

                              @case('Pending Cek')
                            <span class="px-3 py-1 text-xs rounded-full bg-red-50 text-red-600">
                                pending Cek
                            </span>
                            @break

                        @case('Dikembalikan')
                            <span class="px-3 py-1 text-xs rounded-full bg-blue-50 text-blue-600">
                                Selesai
                            </span>
                            @break

                    @endswitch
                </div>

            </div>

           <!-- LIST BARANG -->
<div class="space-y-3">

@foreach($peminjaman->detailBarang as $detail)

<div class="bg-gray-50 rounded-xl p-4 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-y-0">

    <!-- KIRI -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6 flex-1">

        <!-- CHECKBOX -->
       @if(
    $detail->status !== 'Dikembalikan' &&
    $peminjaman->status === 'Disetujui'
)
<input type="checkbox"
       wire:model="selectedDetails"
       value="{{ $detail->id }}"
       class="w-4 h-4 text-[#44a08d] rounded">
@endif
        <!-- DATA -->
        <div class="grid grid-cols-2 sm:flex gap-3 sm:gap-6 text-sm">

            <div>
                <p class="text-[11px] text-gray-400">Kode</p>
                <p class="font-mono break-words">
                    {{ $detail->barangUnit->kode_barang ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-[11px] text-gray-400">Barang</p>
                <p class="font-medium break-words">
                    {{ $detail->barang->nama_barang ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-[11px] text-gray-400">Jumlah</p>
                <p class="font-semibold">
                    {{ $detail->jumlah }} unit
                </p>
            </div>

        </div>

    </div>

    <!-- STATUS -->
    <div class="text-left sm:text-right">

        @php
            $pengembalian = $detail->detailPengembalian ?? null;
        @endphp

        @if($pengembalian)

            @if($pengembalian->status === 'Menunggu Cek')
                <span class="inline-block px-2 py-1 text-[11px] bg-yellow-50 text-yellow-600 rounded">
                    ⏳ Dalam antrean pengecekan petugas
                </span>

            @elseif($pengembalian->status === 'Selesai Cek')
                <span class="inline-block px-2 py-1 text-[11px] bg-blue-50 text-blue-600 rounded">
                    ✔ Barang telah dikembalikan
                </span>
            @endif

        @elseif($detail->status === 'Dipinjam')

            <span class="inline-block px-2 py-1 text-[11px] bg-green-50 text-green-600 rounded">
                Dipinjam
            </span>

        @endif

    </div>

</div>

@endforeach

</div>

<div class="mt-4 sm:mt-6 flex flex-col sm:flex-row sm:justify-end">

<button 
    wire:click="ajukanPengembalian({{ $peminjaman->id }})"
    wire:loading.attr="disabled"
    wire:target="ajukanPengembalian({{ $peminjaman->id }})"

    class="relative flex items-center justify-center
           px-6 py-3 text-sm font-medium
           bg-[#088395] text-white
           hover:bg-[#07707a]
           transition duration-200
           disabled:opacity-60">

    {{-- Spinner --}}
    <svg 
        wire:loading
        wire:target="ajukanPengembalian({{ $peminjaman->id }})"
        class="absolute animate-spin h-4 w-4"
        xmlns="http://www.w3.org/2000/svg" 
        fill="none" 
        viewBox="0 0 24 24">

        <circle 
            cx="12" cy="12" r="10"
            stroke="currentColor"
            stroke-width="4"
            class="opacity-25">
        </circle>

        <path 
            fill="currentColor"
            d="M4 12a8 8 0 018-8v8H4z"
            class="opacity-75">
        </path>

    </svg>

    {{-- Text --}}
    <span 
        wire:loading.class="opacity-0"
        wire:target="ajukanPengembalian({{ $peminjaman->id }})">

        Ajukan Pengembalian

    </span>

</button>

</div>
@empty

<tr>
    <td colspan="6" class="text-center py-16 text-gray-300">
        Belum ada riwayat peminjaman
    </td>
</tr>

@endforelse

</tbody>
            </table>

        </div>

    </div>

    <div class="mt-6">
        {{ $riwayat->links() }}
    </div>

</div>
