<div class="min-h-screen flex items-center justify-center 
            bg-gradient-to-r from-[#093637] to-[#44a08d] 
            relative overflow-hidden
            px-4 sm:px-6">

    <div class="bg-white 
                p-6 sm:p-8 
                rounded-xl 
                shadow-lg 
                w-full 
                max-w-sm sm:max-w-md">

        <!-- ICON / LOGO -->
        <div class="flex justify-center mb-4">

            <div class="bg-gradient-to-r 
                        from-[#093637] to-[#44a08d] 
                        p-3 sm:p-4 
                        rounded-full shadow-md">

                <x-heroicon-o-user-group 
                    class="w-8 h-8 sm:w-10 sm:h-10 text-white"/>

            </div>

        </div>

        <!-- TITLE -->
        <h2 class="text-xl sm:text-2xl 
                   font-bold 
                   text-primary 
                   text-center 
                   mb-6 sm:mb-8">

            Pilih Role Login

        </h2>

        <!-- VERTICAL BUTTON -->
        <div class="flex flex-col gap-4 sm:gap-5">

            <!-- GURU -->
            <a
                wire:navigate
                href="{{ route('guru.login') }}"
                class="group flex items-center gap-3 sm:gap-4
                       rounded-xl 
                       p-4 sm:p-5
                       bg-gradient-to-r 
                       from-[#093637] to-[#44a08d]
                       text-white
                       hover:scale-[1.02] 
                       hover:shadow-lg
                       transition">

                <x-heroicon-o-academic-cap
                    class="w-8 h-8 sm:w-10 sm:h-10 text-white"/>

                <div class="text-left">
                    <p class="font-semibold text-base sm:text-lg">
                        Guru
                    </p>

                    <p class="text-xs sm:text-sm text-white/80">
                        Ajukan Pemakaian Barang
                    </p>
                </div>

            </a>

            <!-- ADMIN -->
            <a
                wire:navigate
                href="{{ route('admin.login') }}"
                class="group flex items-center gap-3 sm:gap-4
                       rounded-xl 
                       p-4 sm:p-5
                       text-white
                       hover:scale-[1.02] 
                       hover:shadow-lg
                       transition"
                style="background-image: radial-gradient(
                    circle farthest-corner at 9.8% 19.4%, 
                    rgba(223,75,75,1) 11.6%, 
                    rgba(20,12,12,1) 90.5%
                );">

                <x-heroicon-o-user-circle
                    class="w-8 h-8 sm:w-10 sm:h-10 text-white"/>

                <div class="text-left">
                    <p class="font-semibold text-base sm:text-lg">
                        Admin
                    </p>

                    <p class="text-xs sm:text-sm text-white/80">
                        Kelola Data Inventory
                    </p>
                </div>

            </a>

        </div>

        <!-- BACK BUTTON -->
        <div class="mt-5 sm:mt-6 text-center">

            <a href="{{ url('/') }}"
                class="inline-flex items-center gap-2 
                       px-5 sm:px-6 
                       py-2.5 
                       text-xs sm:text-sm 
                       font-medium 
                       text-gray-600
                       bg-gray-100
                       rounded-lg
                       hover:bg-gray-200
                       hover:text-gray-800
                       transition duration-200">

                ← Kembali

            </a>

        </div>

        @error('role')
            <p class="text-red-500 text-sm text-center mt-4">
                {{ $message }}
            </p>
        @enderror

    </div>

</div>