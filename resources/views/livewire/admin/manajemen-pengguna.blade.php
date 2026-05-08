<div class="p-6">

    <!-- CONTAINER PUTIH -->
    <div class="bg-white shadow-sm p-6 space-y-6">

        <!-- HEADER + SEARCH -->
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    Manajemen Pengguna
                </h1>
                <p class="text-sm text-gray-500">
                    Kelola akun pengguna sistem
                </p>
            </div>

            <!-- SEARCH -->
            <div class="w-72">
                <input 
                    type="text"
                    wire:model.live="search"
                    placeholder="Cari pengguna..."
                    class="w-full px-4 py-2 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-[#088395]"
                >
            </div>

        </div>


        <!-- TABLE -->
        <div>

            <table class="w-full text-sm">

                <!-- HEADER -->
                <thead class="text-gray-500 text-xs uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Foto</th>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Email</th>
                        <th class="px-6 py-3 text-left">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>

                <!-- BODY -->
                <tbody class="text-gray-700">

                    @forelse($users as $user)

                    <tr class="hover:bg-gray-50 transition">

                       <td class="px-6 py-4">
    <div class="flex items-center justify-center">

        @if($user->foto)
            <img src="{{ asset('storage/' . $user->foto) }}" 
                 class="w-10 h-10 rounded-full object-cover shadow ring-2 ring-gray-200 hover:scale-110 transition">
        @else
            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center ring-2 ring-gray-200">
                <x-heroicon-o-user-circle class="w-6 h-6 text-gray-400"/>
            </div>
        @endif

    </div>
</td>


                        <td class="px-6 py-4 font-medium">
                            {{ $user->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $user->email }}
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        <!-- STATUS -->
                        <td class="px-6 py-4 text-center">

                            @if($user->status == 'aktif')

                                <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-600">
                                    Aktif
                                </span>

                            @else

                                <span class="px-2 py-1 text-xs bg-red-100 text-red-600">
                                    Nonaktif
                                </span>

                            @endif

                        </td>


                    <td class="px-6 py-4 text-center">

@if($user->status == 'aktif')

<button
    wire:click="nonaktifkan({{ $user->id }})"
    wire:loading.attr="disabled"
    wire:target="nonaktifkan({{ $user->id }})"

    class="px-4 py-2 text-sm font-medium
           bg-red-500 hover:bg-red-600
           text-white
           rounded-lg
           shadow-sm
           transition
           min-w-[120px]
           flex items-center justify-center">

    <!-- TEXT NORMAL -->
    <span wire:loading.remove 
          wire:target="nonaktifkan({{ $user->id }})">
        Nonaktifkan
    </span>

    <!-- TEXT LOADING -->
    <span wire:loading 
          wire:target="nonaktifkan({{ $user->id }})">
        Proses...
    </span>

</button>

@else

<button
    wire:click="aktifkan({{ $user->id }})"
    wire:loading.attr="disabled"
    wire:target="aktifkan({{ $user->id }})"

    class="px-4 py-2 text-sm font-medium
           bg-emerald-500 hover:bg-emerald-600
           text-white
           rounded-lg
           shadow-sm
           transition
           min-w-[120px]
           flex items-center justify-center">

    <!-- TEXT NORMAL -->
    <span wire:loading.remove 
          wire:target="aktifkan({{ $user->id }})">
        Aktifkan
    </span>

    <!-- TEXT LOADING -->
    <span wire:loading 
          wire:target="aktifkan({{ $user->id }})">
        Proses...
    </span>

</button>

@endif

</td>
                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-400">
                            Tidak ada data pengguna
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>