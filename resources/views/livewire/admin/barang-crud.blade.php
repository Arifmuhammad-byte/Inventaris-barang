<div class="min-h-screen bg-gray-100/80 p-6 font-sans">

    {{-- CONTAINER UTAMA --}}
    <div class="bg-white shadow-xl p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <h2 class="text-lg font-semibold text-gray-700">
                Daftar Barang
            </h2>

            <div class="flex items-center gap-4">

               <div class="relative w-72">
    
    <!-- Input -->
    <input type="text"
        wire:model.live.debounce.500ms="search"
        placeholder="Cari barang..."
        class="w-full pl-10 pr-4 py-2.5 text-sm
               bg-white border border-gray-200
               rounded-xl shadow-sm
               focus:outline-none focus:ring-2 focus:ring-[#088395]/40
               focus:border-[#088395]
               transition duration-200"
    />
   
   <!-- Loading Spinner -->
<div wire:loading wire:target="search"
     class="absolute right-3 top-1/2 -translate-y-1/2">

    <svg class="animate-spin h-5 w-5 text-[#088395]"
         viewBox="0 0 24 24">
        
        <!-- Circle background -->
        <circle 
            class="opacity-20"
            cx="12" cy="12" r="10"
            stroke="currentColor"
            stroke-width="4"
            fill="none">
        </circle>

        <!-- Animated arc -->
        <path 
            class="opacity-90"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v8H4z">
        </path>

    </svg>

</div>
</div>

                {{-- BUTTON TAMBAH --}}
                <button wire:click="openModal"
                    class="bg-[#09637E] hover:bg-[#0b7f9e] text-white font-semibold py-2 px-4 shadow flex items-center gap-2 transition duration-300">

                    Tambah Barang

                    <svg wire:loading wire:target="openModal"
                        class="animate-spin h-4 w-4 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>

                </button>

            </div>
        </div>

     {{-- TABLE --}}
<div class="overflow-x-auto shadow-lg bg-white">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-medium">No</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Foto</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Nama Barang</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Jumlah</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Stok Tersisa</th> {{-- Baru --}}
                <th class="px-4 py-2 text-left text-sm font-medium">Baik</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Rusak Ringan</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Rusak Berat</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Hilang</th>
                <th class="px-4 py-2 text-left text-sm font-medium">Keterangan</th>
                <th class="px-4 py-2 text-center text-sm font-medium">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($barangs as $index => $barang)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2 text-sm">{{ $index + 1 }}</td>
                     <!-- FOTO BARANG -->
    <td class="px-4 py-2">

        @if($barang->foto)

            <img 
                src="{{ asset('storage/' . $barang->foto) }}"
                class="w-12 h-12 object-cover rounded-lg shadow
                       hover:scale-110 transition duration-200">

        @else

            <div class="w-12 h-12 bg-gray-100 rounded-lg 
                        flex items-center justify-center shadow">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-6 w-6 text-gray-400"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M4 16l4-4 4 4 4-4 4 4"/>

                </svg>

            </div>

        @endif

    </td>
                    <td class="px-4 py-2 text-sm">{{ $barang->nama_barang }}</td>
                    <td class="px-4 py-2 text-sm">{{ $barang->jumlah_total }}</td>

                    {{-- Stok Tersisa --}}
                    {{-- Stok Tersisa (ambil dari kondisi_baik) --}}
<td class="px-4 py-2 text-sm">
    {{ $barang->kondisi_baik }}
</td>
                    @php

$baik = $barang->barangUnits()
    ->where('kondisi', 'Baik')
    ->count();

$rusakRingan = $barang->barangUnits()
    ->where('kondisi', 'Rusak Ringan')
    ->count();

$rusakBerat = $barang->barangUnits()
    ->where('kondisi', 'Rusak Berat')
    ->count();

$hilang = $barang->barangUnits()
    ->where('kondisi', 'Hilang')
    ->count();

@endphp

<td class="px-4 py-2 text-sm">
    {{ $baik }}
</td>

<td class="px-4 py-2 text-sm">
    {{ $rusakRingan }}
</td>

<td class="px-4 py-2 text-sm">
    {{ $rusakBerat }}
</td>

<td class="px-4 py-2 text-sm">
    {{ $hilang }}
</td>
                    <td class="px-4 py-2 text-sm">{{ $barang->keterangan }}</td>

                    <td class="px-4 py-2 text-center flex justify-center gap-2">
                          <button 
    wire:click="edit({{ $barang->id }})"
    wire:loading.attr="disabled"
    wire:target="edit({{ $barang->id }})"
    class="relative bg-yellow-500 hover:bg-yellow-600 text-white 
           p-2 rounded-lg shadow-md 
           transition duration-200 hover:scale-105 active:scale-95
           flex items-center justify-center
           min-w-[36px] min-h-[36px]">

    <!-- ICON EDIT -->
    <svg wire:loading.class="opacity-0"
         wire:target="edit({{ $barang->id }})"
         xmlns="http://www.w3.org/2000/svg" 
         class="h-5 w-5" 
         fill="none" 
         viewBox="0 0 24 24" 
         stroke="currentColor"
         stroke-width="2">
        <path stroke-linecap="round" 
            stroke-linejoin="round" 
            d="M16.862 4.487l1.651-1.651a1.875 1.875 0 112.652 
               2.652L8.25 18.403 4 20l1.597-4.25L16.862 4.487z"/>
    </svg>

    <!-- SPINNER MODERN -->
    <svg wire:loading 
         wire:target="edit({{ $barang->id }})"
         class="animate-spin h-5 w-5 absolute"
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

<button 
    wire:click="confirmDelete({{ $barang->id }})"
    class="bg-red-500 hover:bg-red-600 text-white 
           p-2 rounded-lg shadow-md 
           transition duration-200 hover:scale-105">

    <svg xmlns="http://www.w3.org/2000/svg" 
        class="h-5 w-5" 
        viewBox="0 0 20 20" 
        fill="currentColor">
        <path fill-rule="evenodd" 
            d="M8.257 3.099c.366-.446.957-.724 
               1.585-.724h.316c.628 0 1.219.278 
               1.585.724L12 4h3a1 1 0 110 2h-1l-.867 
               10.142A2 2 0 0111.138 18H8.862a2 2 0 
               01-1.995-1.858L6 6H5a1 1 0 110-2h3l.257-.901z" 
            clip-rule="evenodd" />
    </svg>

    

</button>



                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

 @if($showModal)
<div class="fixed inset-0 z-50 flex items-center justify-center 
            bg-gray-900/40">

<div class="bg-white w-full max-w-3xl 
             shadow-xl 
            p-4 relative">

<!-- CLOSE -->
<button wire:click="closeModal"
class="absolute top-3 right-3 w-8 h-8 flex items-center 
       justify-center rounded-lg
       hover:bg-gray-100 
       text-gray-500 text-lg">

×
</button>

<!-- HEADER -->
<div class="mb-3 border-b border-gray-100 pb-2">

<h2 class="text-base font-semibold text-gray-800">
{{ $barang_id ? 'Edit Barang' : 'Tambah Barang' }}
</h2>

<p class="text-xs text-gray-400 mt-1">
Kelola data barang dan kondisi unit
</p>

</div>

<form wire:submit.prevent="store">

<div class="grid grid-cols-2 gap-4">

<!-- ========================= -->
<!-- KIRI -->
<!-- ========================= -->

<div class="space-y-2 text-sm">

<!-- FOTO -->
<div>
<label class="text-xs text-gray-500">
Foto Barang
</label>

<input type="file"
wire:model="foto"
accept="image/*"
class="w-full mt-1 border border-gray-200 
       rounded-lg px-3 py-1.5 text-sm 
       focus:ring-2 focus:ring-[#09637E]/20">
</div>

<!-- NAMA -->
<div>
<label class="text-xs text-gray-500">
Nama Barang
</label>

<input type="text"
wire:model.defer="nama_barang"
class="w-full mt-1 border border-gray-200 
       rounded-lg px-3 py-1.5 
       focus:ring-2 focus:ring-[#09637E]/20">
</div>

<!-- KATEGORI -->
<div>

<label class="text-xs text-gray-500">
Kategori
</label>

<select
wire:model.defer="kategori"
class="w-full mt-1 border border-gray-200 
       rounded-lg px-3 py-1.5 bg-white
       focus:ring-2 focus:ring-[#09637E]/20">

<option value="">Pilih Kategori</option>

<option value="Barang Laboratorium">
Barang Laboratorium
</option>

<option value="Alat Olahraga">
Alat Olahraga
</option>

<option value="Lainnya">
Lainnya
</option>

</select>

</div>

<!-- LOKASI -->
<div>

<label class="text-xs text-gray-500">
Lokasi
</label>

<select
wire:model.defer="lokasi"
class="w-full mt-1 border border-gray-200 
       rounded-lg px-3 py-1.5 bg-white
       focus:ring-2 focus:ring-[#09637E]/20">

<option value="">Pilih Lokasi</option>

<option value="Lab Fisika">Lab Fisika</option>
<option value="Lab Kimia">Lab Kimia</option>
<option value="Lab Biologi">Lab Biologi</option>
<option value="Lab Komputer">Lab Komputer</option>
<option value="Gudang Olahraga">
Gudang Olahraga
</option>
<option value="Lainnya">Lainnya</option>

</select>

</div>

<!-- PREFIX -->
<div>
<label class="text-xs text-gray-500">
Kode Prefix
</label>

<input type="text"
wire:model.defer="kode_barang_prefix"
class="w-full mt-1 border border-gray-200 
       rounded-lg px-3 py-1.5
       focus:ring-2 focus:ring-[#09637E]/20">
</div>

<!-- JUMLAH -->
<div>
<label class="text-xs text-gray-500">
Jumlah Total Unit
</label>

<input type="number"
wire:model.defer="jumlah_total"
class="w-full mt-1 border border-gray-200 
       rounded-lg px-3 py-1.5
       focus:ring-2 focus:ring-[#09637E]/20">
</div>

<!-- KETERANGAN -->
<div>
<label class="text-xs text-gray-500">
Keterangan
</label>

<textarea
wire:model.defer="keterangan"
rows="2"
class="w-full mt-1 border border-gray-200 
       rounded-lg px-3 py-1.5
       focus:ring-2 focus:ring-[#09637E]/20">
</textarea>
</div>

</div>

<!-- ========================= -->
<!-- KANAN -->
<!-- ========================= -->

<div>

<h3 class="text-sm font-semibold text-gray-700 mb-2">
Daftar Unit Barang
</h3>

<div class="h-[300px] overflow-y-auto 
            space-y-1 
            bg-gray-50 rounded-lg p-2">

@if($barang_id)

@foreach($barangUnits as $unit)

<div class="flex items-center justify-between 
            px-3 py-2 
            bg-white rounded-lg 
            border border-gray-100">

<div>

<span class="font-mono text-xs text-gray-700">
{{ $unit->kode_barang }}
</span>

<div class="text-[10px] text-gray-400">
{{ $unit->kondisi }}
</div>

</div>

<select
wire:model="kondisiUnit.{{ $unit->id }}"
wire:change="updateKondisiUnit({{ $unit->id }})"
class="text-xs px-2 py-1 
       rounded-md border border-gray-200 
       bg-gray-50">

<option value="">Pilih</option>
<option value="Baik">Baik</option>
<option value="Rusak Ringan">Rusak Ringan</option>
<option value="Rusak Berat">Rusak Berat</option>
<option value="Hilang">Hilang</option>

</select>

</div>

@endforeach

@else

<div class="text-xs text-gray-400 
            p-6 text-center">
Unit akan muncul setelah barang disimpan
</div>

@endif

</div>

</div>

</div>

<!-- FOOTER -->

<div class="flex justify-end gap-2 mt-3 border-t border-gray-100 pt-3">

<button type="button"
wire:click="closeModal"
class="px-4 py-1.5 
       bg-gray-100 
       rounded-lg 
       text-sm 
       hover:bg-gray-200">
Batal
<button type="submit"
    wire:loading.attr="disabled"
    class="px-5 py-1.5 
           bg-[#09637E] 
           rounded-lg 
           text-white 
           text-sm 
           hover:bg-[#07556b]
           flex items-center gap-2">

    <!-- SPINNER -->
    <svg wire:loading
        wire:target="store"
        class="animate-spin h-4 w-4 text-white"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24">

        <circle class="opacity-25"
            cx="12" cy="12" r="10"
            stroke="currentColor"
            stroke-width="4"></circle>

        <path class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8v8z"></path>
    </svg>

    <!-- TEXT -->
    <span wire:loading.remove wire:target="store">
        {{ $barang_id ? 'Update' : 'Simpan' }}
    </span>

    <span wire:loading wire:target="store">
        Loading...
    </span>

</button>

</div>

</form>

</div>

</div>
@endif
</div>
<script>
document.addEventListener('livewire:init', () => {

    Livewire.on('confirm-delete', (event) => {

        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#09637E',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {

            if (result.isConfirmed) {

                Livewire.dispatch('deleteBarang', {
                    id: event.id
                });

            }

        });

    });

});
</script>
<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('deleted', () => {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Data berhasil dihapus',
            icon: 'success',
            confirmButtonColor: '#088395'
        });
    });
});
</script>

<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal-success', (data) => {
        Swal.fire({
            title: data.title,
            text: data.text,
            icon: data.icon,
            confirmButtonColor: '#088395',
            timer: 2000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    });
});
</script>