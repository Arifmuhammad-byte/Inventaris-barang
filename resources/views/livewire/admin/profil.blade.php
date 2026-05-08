<div class="p-6">

    <!-- CONTAINER -->
    <div class="bg-white shadow-sm p-6 space-y-6">

        <!-- HEADER -->
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">
                Profil Admin
            </h1>
            <p class="text-sm text-gray-500">
                Informasi akun administrator
            </p>
        </div>

        <!-- PROFIL -->
        <div class="flex items-center gap-6">

            <!-- ICON -->
            <div class="w-20 h-20 flex items-center justify-center bg-gray-100 rounded-full">
                <x-heroicon-o-user-circle class="w-14 h-14 text-gray-400"/>
            </div>

            <!-- NAMA -->
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $name }}
                </h2>

                <p class="text-sm text-gray-500">
                    {{ $email }}
                </p>
            </div>

        </div>


        <!-- INFORMASI AKUN -->
        <div class="grid grid-cols-2 gap-6 pt-4">

            <!-- Nama -->
            <div>
                <p class="text-sm text-gray-500">Nama</p>
                <p class="font-medium text-gray-800">
                    {{ $name }}
                </p>
            </div>

            <!-- Email -->
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium text-gray-800">
                    {{ $email }}
                </p>
            </div>

            <!-- Role -->
            <div>
                <p class="text-sm text-gray-500">Role</p>
                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-600">
                    Admin
                </span>
            </div>

            <!-- Status -->
            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="px-2 py-1 text-xs bg-emerald-100 text-emerald-600">
                    Aktif
                </span>
            </div>

            <!-- Tanggal -->
            <div>
                <p class="text-sm text-gray-500">Tanggal Login</p>
                <p class="font-medium text-gray-800">
                    {{ now()->format('d M Y') }}
                </p>
            </div>

        </div>

    </div>

</div>