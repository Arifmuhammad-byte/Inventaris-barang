<div class="px-4 py-6 bg-gray-50 min-h-screen w-full overflow-x-hidden">

    @if(session()->has('message'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 text-sm">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white shadow-sm overflow-hidden">

        <!-- HEADER DALAM CONTAINER -->
        <div class="px-6 py-6 border-b border-gray-100">
            <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">
                Data Pengembalian
            </h2>
        </div>

        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-6 py-4 text-left">No</th>
                    <th class="px-6 py-4 text-left">ID Peminjaman</th>
                    <th class="px-6 py-4 text-left">Nama Peminjam</th>
                    <th class="px-6 py-4 text-left">Kode barang</th>
                    <th class="px-6 py-4 text-left">Nama Barang</th>
                    <th class="px-6 py-4 text-left">Tanggal Pinjam</th>
                    <th class="px-6 py-4 text-left">Tanggal Kembali</th>
                    <th class="px-6 py-4 text-left">Jumlah</th>
                    <th class="px-6 py-4 text-left">Update Kondisi</th>
                </tr>
            </thead>

           <tbody class="divide-y divide-gray-100">

@forelse($pengembalians->groupBy('pengembalian_id') as $index => $details)

@php
    $first = $details->first();
@endphp

<tr wire:key="pengembalian-{{ $first->pengembalian_id }}" class="hover:bg-gray-50 transition">

    <!-- NO -->
    <td class="px-6 py-4">
        {{ $loop->iteration }}
    </td>

    <!-- ID PEMINJAMAN -->
    <td class="px-6 py-4 font-medium text-blue-600">
        #{{ $first->pengembalian->peminjaman->id ?? '-' }}
    </td>

    <!-- NAMA -->
    <td class="px-6 py-4">
        {{ $first->peminjaman->user->name ?? '-' }}
    </td>

    <!-- KODE BARANG (BANYAK DALAM 1 CELL) -->
    <td class="px-6 py-4 font-mono text-gray-700">

        <div class="space-y-1">

            @foreach($details as $d)

                <div>
                    {{ $d->barangUnit->kode_barang ?? '-' }}
                </div>

            @endforeach

        </div>

    </td>

   <td class="px-6 py-4 font-medium">

    <div class="space-y-1">

        @foreach($details->groupBy('barang_id') as $barangId => $items)

            <div>
                {{ $items->first()->barang->nama_barang ?? '-' }}
            </div>

        @endforeach

    </div>

</td>

    <!-- TANGGAL PINJAM -->
    <td class="px-6 py-4 text-gray-500">
        {{ date('d M Y', strtotime($first->peminjaman->tanggal_pinjam)) }}
    </td>

    <!-- TANGGAL KEMBALI -->
    <td class="px-6 py-4 text-gray-500">
        {{ date('d M Y', strtotime($first->pengembalian->tanggal_pengembalian)) }}
    </td>

    <!-- JUMLAH TOTAL -->
    <td class="px-6 py-4 font-semibold">
        {{ $details->sum('jumlah_kembali') }}
    </td>


    <!-- BUTTON CEK (1 SAJA) -->
    <td class="px-6 py-4 text-center">

@php
$isChecked = $details->every(function ($d) {
    return $d->status === 'Selesai Cek';
});
@endphp
      @if(!$isChecked)

    <button 
        wire:click="openModal({{ $first->pengembalian_id }})"
        wire:loading.attr="disabled"
        wire:target="openModal({{ $first->pengembalian_id }})"
        class="relative flex items-center justify-center gap-2
               px-4 py-2 text-xs font-medium
               bg-[#088395] text-white
               hover:bg-[#07707a]
               transition duration-200
               disabled:opacity-50
               min-w-[70px]">

        {{-- Spinner --}}
        <svg 
            wire:loading 
            wire:target="openModal({{ $first->pengembalian_id }})"
            class="animate-spin h-4 w-4"
            xmlns="http://www.w3.org/2000/svg" 
            fill="none" 
            viewBox="0 0 24 24">

            <circle cx="12" cy="12" r="10"
                stroke="currentColor"
                stroke-width="4"
                class="opacity-25"></circle>

            <path fill="currentColor"
                class="opacity-75"
                d="M4 12a8 8 0 018-8v8H4z"></path>
        </svg>

        <span wire:loading.remove wire:target="openModal({{ $first->pengembalian_id }})">
            Cek
        </span>

    </button>

@else

    <button 
        disabled
        class="px-4 py-2 text-xs bg-gray-100 text-gray-400 cursor-not-allowed">
        Sudah Dicek
    </button>

@endif

    </td>

</tr>

@empty

<tr>
    <td colspan="8" class="text-center py-10 text-gray-300">
        Tidak ada data pengembalian
    </td>
</tr>

@endforelse

</tbody>
        </table>
    </div>


 @if($showModal)
<div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50">

    <!-- MODAL -->
    <div class="bg-white w-[680px] shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="flex justify-between items-center px-7 py-5 border-b border-gray-100 bg-white">

            <div>
                <h3 class="text-lg font-semibold text-gray-800">
                    Update Kondisi Barang
                </h3>

                <p class="text-xs text-gray-400 mt-1">
                    Pilih kondisi setiap unit barang
                </p>
            </div>

          <button 
    wire:click="closeModal"
    wire:loading.attr="disabled"
    wire:target="closeModal"
    class="w-9 h-9 flex items-center justify-center 
           hover:bg-gray-100 transition text-gray-500">

    <!-- SPINNER -->
    <svg 
        wire:loading 
        wire:target="closeModal"
        class="animate-spin h-4 w-4"
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

    <!-- ICON NORMAL -->
    <span wire:loading.remove wire:target="closeModal">
        ×
    </span>

</button>
        </div>

        <!-- CONTENT -->
        <div class="px-7 py-6 space-y-6 max-h-[480px] overflow-y-auto">

            @foreach($selectedDetail->groupBy('barang_id') as $barangId => $details)

                @php
                    $first = $details->first();
                @endphp

                <!-- CARD BARANG -->
                <div class="bg-gray-50 p-5 space-y-4">

                    <!-- HEADER BARANG -->
                    <div class="flex items-center justify-between">

                        <div class="flex flex-col">

                            <span class="font-semibold text-gray-800">
                                {{ $first->barang->nama_barang }}
                            </span>

                            <span class="text-xs text-gray-400">
                                {{ $details->count() }} unit barang
                            </span>

                        </div>

                        <div class="text-xs px-3 py-1 bg-blue-50 text-blue-600">
                            Unit List
                        </div>

                    </div>

                    <!-- LIST UNIT -->
                    <div class="grid gap-2">

                        @foreach($details as $detail)

                            <div class="flex items-center justify-between 
                                        bg-white px-4 py-3 
                                        border border-gray-100">

                                <!-- KODE -->
                                <div class="flex items-center gap-3">

                                    <div class="w-2 h-2 bg-gray-300"></div>

                                    <div class="flex flex-col">

<span class="font-mono text-sm text-gray-700">
    {{ $detail->barangUnit->kode_barang ?? '-' }}
</span>

@if($detail->status === 'Selesai Cek')
    <span class="text-xs text-green-500">
        ✔ Sudah Dicek
    </span>
@endif

</div>

                                </div>

                                <!-- SELECT -->
                               <select
    wire:model.defer="kondisiBarang.{{ $detail->id }}"
    @if($detail->status === 'Selesai Cek') disabled @endif
    class="text-sm border border-gray-200 px-3 py-2
           focus:ring-2 focus:ring-blue-500
           focus:outline-none bg-white
           disabled:bg-gray-100 disabled:text-gray-400">
                                    class="text-sm border border-gray-200 px-3 py-2
                                           focus:ring-2 focus:ring-blue-500
                                           focus:outline-none bg-white">

                                    <option value="">Pilih Kondisi</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                    <option value="Hilang">Hilang</option>

                                </select>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endforeach

        </div>

        <!-- FOOTER -->
        <div class="flex justify-between items-center px-7 py-5 border-t border-gray-100 bg-gray-50">

            <span class="text-xs text-gray-400">
                Pastikan semua kondisi sudah dipilih
            </span>

            <div class="flex gap-3">

                <button
    wire:click="closeModal"
    wire:loading.attr="disabled"
    wire:target="closeModal"
    class="flex items-center gap-2 px-5 py-2 text-sm text-gray-600 hover:text-gray-800 transition">

    <!-- spinner -->
    <svg 
        wire:loading 
        wire:target="closeModal"
        class="animate-spin h-4 w-4"
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

    <span wire:loading.remove wire:target="closeModal">
        Batal
    </span>

    <span wire:loading wire:target="closeModal">
        Membatalkan...
    </span>

</button>

                <button
                    wire:click="updatePengembalian"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-6 py-2 text-sm 
                           bg-blue-600 text-white hover:bg-blue-700
                           transition disabled:opacity-50">

                    <!-- SPINNER -->
                    <svg wire:loading class="animate-spin h-4 w-4"
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

                    <span wire:loading.remove>
                        Simpan Perubahan
                    </span>

                    <span wire:loading>
                        Menyimpan...
                    </span>

                </button>

            </div>

        </div>

    </div>

</div>
@endif
</div>
