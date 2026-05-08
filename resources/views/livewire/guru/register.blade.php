<div class="min-h-screen flex items-center justify-center 
bg-gradient-to-r from-[#093637] to-[#44a08d] 
relative overflow-hidden
px-4 sm:px-6">

    <!-- CARD -->
    <div class="relative z-10 
                bg-white 
                w-full 
                max-w-sm sm:max-w-md 
                p-6 sm:p-8 
                rounded-xl 
                shadow-lg">

        <!-- ICON -->
        <div class="flex justify-center mb-6">

            <div class="w-14 h-14 sm:w-16 sm:h-16 
                rounded-full 
                bg-gradient-to-br 
                from-[#093637] to-[#44a08d]
                border-2 border-white
                flex items-center justify-center
                shadow-lg shadow-[#44a08d]/30">

                <x-heroicon-o-user-plus 
                    class="w-7 h-7 sm:w-8 sm:h-8 text-white" />

            </div>

        </div>

        <!-- TITLE -->
        <h2 class="text-xl sm:text-2xl 
                   font-bold 
                   text-[#09637E] 
                   text-center 
                   mb-6">

            Register Guru

        </h2>

        <form wire:submit.prevent="register" 
              class="space-y-4">

            <!-- NAMA -->
            <div class="relative">

                <span class="absolute inset-y-0 left-0 
                             flex items-center 
                             pl-3 text-gray-400">

                    <x-heroicon-o-user class="w-4 h-4 sm:w-5 sm:h-5"/>

                </span>

                <input 
                    type="text" 
                    wire:model="name"

                    placeholder="Nama Lengkap"

                    class="w-full 
                           pl-9 sm:pl-10 
                           px-3 sm:px-4 
                           py-2 
                           text-sm
                           border border-gray-300 
                           rounded-lg
                           focus:outline-none 
                           focus:ring-2 
                           focus:ring-[#44a08d]">

                @error('name')
                    <span class="text-red-500 text-xs sm:text-sm">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <!-- EMAIL -->
            <div class="relative">

                <span class="absolute inset-y-0 left-0 
                             flex items-center 
                             pl-3 text-gray-400">

                    <x-heroicon-o-envelope class="w-4 h-4 sm:w-5 sm:h-5"/>

                </span>

                <input 
                    type="email" 
                    wire:model="email"

                    placeholder="Email"

                    class="w-full 
                           pl-9 sm:pl-10 
                           px-3 sm:px-4 
                           py-2 
                           text-sm
                           border border-gray-300 
                           rounded-lg
                           focus:outline-none 
                           focus:ring-2 
                           focus:ring-[#44a08d]">

                @error('email')
                    <span class="text-red-500 text-xs sm:text-sm">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <!-- PASSWORD -->
            <div class="relative">

                <span class="absolute inset-y-0 left-0 
                             flex items-center 
                             pl-3 text-gray-400">

                    <x-heroicon-o-lock-closed class="w-4 h-4 sm:w-5 sm:h-5"/>

                </span>

                <input 
                    type="password" 
                    wire:model="password"

                    placeholder="Password"

                    class="w-full 
                           pl-9 sm:pl-10 
                           px-3 sm:px-4 
                           py-2 
                           text-sm
                           border border-gray-300 
                           rounded-lg
                           focus:outline-none 
                           focus:ring-2 
                           focus:ring-[#44a08d]">

                @error('password')
                    <span class="text-red-500 text-xs sm:text-sm">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <!-- KONFIRMASI PASSWORD -->
            <div class="relative">

                <span class="absolute inset-y-0 left-0 
                             flex items-center 
                             pl-3 text-gray-400">

                    <x-heroicon-o-lock-closed class="w-4 h-4 sm:w-5 sm:h-5"/>

                </span>

                <input 
                    type="password" 
                    wire:model="password_confirmation"

                    placeholder="Konfirmasi Password"

                    class="w-full 
                           pl-9 sm:pl-10 
                           px-3 sm:px-4 
                           py-2 
                           text-sm
                           border border-gray-300 
                           rounded-lg
                           focus:outline-none 
                           focus:ring-2 
                           focus:ring-[#44a08d]">

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="register"

                class="w-full py-2.5 
                       text-white 
                       text-sm
                       rounded-lg 
                       font-semibold
                       bg-gradient-to-r 
                       from-[#093637] to-[#44a08d]
                       hover:scale-[1.02] 
                       hover:shadow-lg
                       transition duration-200
                       flex items-center justify-center gap-2">

                <!-- SPINNER -->
                <x-heroicon-o-arrow-path
                    wire:loading
                    wire:target="register"
                    class="w-4 h-4 animate-spin" />

                <span wire:loading.remove wire:target="register">
                    Daftar
                </span>

                <span wire:loading wire:target="register">
                    Loading...
                </span>

            </button>

        </form>

        <!-- LOGIN LINK -->
        <p class="text-xs sm:text-sm 
                  text-center 
                  text-gray-500 
                  mt-6">

            Sudah punya akun?

            <a href="{{ route('guru.login') }}" 
               class="text-[#44a08d] 
                      font-semibold 
                      hover:underline">

                Login

            </a>

        </p>

    </div>

</div>