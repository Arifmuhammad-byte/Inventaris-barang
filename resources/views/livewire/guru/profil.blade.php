<div class="p-4 sm:p-6">

    <!-- CONTAINER -->
    <div class="bg-white shadow-sm p-4 sm:p-6 space-y-4 sm:space-y-6">

        <!-- HEADER -->
        <div>
            <h1 class="text-lg sm:text-2xl font-semibold text-gray-800">
                Profil Saya
            </h1>
            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                Informasi akun pengguna
            </p>
        </div>

        <!-- PROFIL CARD -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-6">

            <!-- FOTO PROFIL -->
            <div class="flex flex-col items-center w-full sm:w-auto">

                <div class="relative group w-24 h-24 sm:w-28 sm:h-28">

                    @if ($foto)
                        <img src="{{ $foto->temporaryUrl() }}" 
                             class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover shadow">
                    @elseif($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" 
                             class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover shadow">
                    @else
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gray-100 flex items-center justify-center shadow">
                            <x-heroicon-o-user-circle class="w-14 h-14 sm:w-16 sm:h-16 text-gray-400"/>
                        </div>
                    @endif

                    <!-- INPUT -->
                    <input type="file" wire:model="foto" accept="image/*" class="hidden" id="uploadFoto">

                    <!-- OVERLAY -->
                    <label for="uploadFoto"
                        class="absolute inset-0 bg-black/40 rounded-full 
                               flex items-center justify-center 
                               text-white text-[10px] sm:text-xs font-medium
                               opacity-0 group-hover:opacity-100
                               cursor-pointer transition">
                        Ubah
                    </label>

                    <!-- LOADING -->
                    <div wire:loading.flex wire:target="foto"
                        class="absolute inset-0 bg-black/60 rounded-full 
                               items-center justify-center flex flex-col text-white text-[10px] sm:text-xs">

                        <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mb-1"></div>
                        Uploading...
                    </div>
                </div>

                <!-- BUTTON -->
                @if ($foto)
                    <button 
                        wire:click="updateFoto"
                        wire:loading.attr="disabled"
                        class="mt-3 sm:mt-4 w-full sm:w-auto px-4 sm:px-5 py-2 text-xs sm:text-sm font-medium text-white 
                               bg-gradient-to-r from-[#093637] to-[#44a08d]
                               rounded-lg shadow hover:opacity-90 transition">

                        <span wire:loading.remove wire:target="updateFoto">
                            Simpan Foto
                        </span>

                        <span wire:loading wire:target="updateFoto">
                            Menyimpan...
                        </span>
                    </button>
                @endif

                <!-- NOTIF -->
                @if (session()->has('success'))
                    <p class="text-green-500 text-xs sm:text-sm mt-2 sm:mt-3 text-center sm:text-left">
                        {{ session('success') }}
                    </p>
                @endif

            </div>

            <!-- DATA USER -->
            <div class="text-center sm:text-left">
                <h2 class="text-base sm:text-lg font-semibold text-gray-800 break-words">
                    {{ $user->name }}
                </h2>

                <p class="text-xs sm:text-sm text-gray-500 break-words">
                    {{ $user->email }}
                </p>
            </div>

        </div>


        <!-- INFORMASI AKUN -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 pt-4 sm:pt-6">

            <div>
                <p class="text-xs sm:text-sm text-gray-500">Nama</p>
                <p class="font-medium text-gray-800 break-words">
                    {{ $user->name }}
                </p>
            </div>

            <div>
                <p class="text-xs sm:text-sm text-gray-500">Email</p>
                <p class="font-medium text-gray-800 break-words">
                    {{ $user->email }}
                </p>
            </div>

            <div>
                <p class="text-xs sm:text-sm text-gray-500">Role</p>
                <p class="font-medium text-gray-800">
                    {{ $user->role }}
                </p>
            </div>

            <div>
                <p class="text-xs sm:text-sm text-gray-500">Status</p>

                @if($user->status == 'aktif')
                    <span class="inline-block px-2 py-1 text-xs bg-emerald-100 text-emerald-600 rounded">
                        Aktif
                    </span>
                @else
                    <span class="inline-block px-2 py-1 text-xs bg-red-100 text-red-600 rounded">
                        Nonaktif
                    </span>
                @endif
            </div>

            <div class="sm:col-span-2">
                <p class="text-xs sm:text-sm text-gray-500">Tanggal Dibuat</p>
                <p class="font-medium text-gray-800">
                    {{ $user->created_at->format('d M Y') }}
                </p>
            </div>

        </div>

    </div>

</div>