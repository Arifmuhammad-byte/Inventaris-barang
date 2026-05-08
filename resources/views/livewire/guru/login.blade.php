<div class="min-h-screen flex items-center justify-center 
bg-gradient-to-r from-[#093637] to-[#44a08d] 
relative overflow-hidden
px-4 sm:px-6">

    <!-- LOGIN CARD -->
    <div class="relative z-10 
                bg-white 
                p-6 sm:p-8 
                rounded-xl 
                shadow-lg 
                w-full 
                max-w-sm sm:max-w-md">

        <!-- ICON -->
        <div class="flex justify-center mb-4">

            <div class="w-14 h-14 sm:w-16 sm:h-16 
                rounded-full 
                bg-gradient-to-r 
                from-[#093637] to-[#44a08d]
                border-2 border-white
                flex items-center justify-center 
                shadow-md">

                <x-heroicon-o-identification 
                    class="w-8 h-8 sm:w-10 sm:h-10 text-white" />

            </div>

        </div>

        <!-- TITLE -->
        <h2 class="text-xl sm:text-2xl 
                   font-bold 
                   text-center 
                   text-[#09637E] 
                   mb-5 sm:mb-6">

            Login Guru

        </h2>

        <form wire:submit.prevent="login" 
              class="space-y-4">

            <!-- EMAIL -->
            <div>

                <label class="text-xs sm:text-sm 
                               font-medium text-gray-600">

                    Email

                </label>

                <div class="relative mt-1">

                    <span class="absolute inset-y-0 left-0 
                                 flex items-center 
                                 pl-3 text-gray-400">

                        <x-heroicon-o-envelope 
                            class="w-4 h-4 sm:w-5 sm:h-5" />

                    </span>

                    <input
                        type="email"
                        wire:model.defer="email"

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

                @error('email')
                    <span class="text-red-500 text-xs sm:text-sm">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <!-- PASSWORD -->
            <div>

                <label class="text-xs sm:text-sm 
                               font-medium text-gray-600">

                    Password

                </label>

                <div class="relative mt-1">

                    <span class="absolute inset-y-0 left-0 
                                 flex items-center 
                                 pl-3 text-gray-400">

                        <x-heroicon-o-lock-closed 
                            class="w-4 h-4 sm:w-5 sm:h-5" />

                    </span>

                    <input
                        type="password"
                        wire:model.defer="password"

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

                @error('password')
                    <span class="text-red-500 text-xs sm:text-sm">
                        {{ $message }}
                    </span>
                @enderror

            </div>

            <!-- BUTTON LOGIN -->
            <button
                wire:loading.attr="disabled"
                wire:target="login"

                class="w-full flex items-center justify-center gap-2
                       bg-gradient-to-r 
                       from-[#093637] to-[#44a08d]
                       text-white 
                       py-2.5 
                       text-sm
                       rounded-lg 
                       font-semibold
                       hover:scale-[1.02] 
                       hover:shadow-lg
                       transition 
                       duration-200 
                       disabled:opacity-70">

                <x-heroicon-o-arrow-path
                    wire:loading
                    wire:target="login"
                    class="w-4 h-4 animate-spin" />

                <span wire:loading.remove wire:target="login">
                    Login
                </span>

                <span wire:loading wire:target="login">
                    Loading...
                </span>

            </button>

        </form>

        <!-- REGISTER -->
        <p class="text-xs sm:text-sm 
                  text-center 
                  text-gray-500 
                  mt-5">

            Belum punya akun?

            <a
                href="{{ route('guru.register') }}"
                class="text-[#44a08d] 
                       font-semibold 
                       hover:underline">

                Daftar

            </a>

        </p>

        <!-- BACK BUTTON -->
        <div class="mt-4 flex justify-center">

            <button
                type="button"
                wire:click="back"
                wire:loading.attr="disabled"

                class="inline-flex items-center gap-2 
                       text-xs sm:text-sm 
                       font-semibold
                       text-[#09637E] 
                       hover:text-[#44a08d] 
                       transition">

                <span wire:loading.remove wire:target="back">
                    ← Kembali
                </span>

                <span wire:loading wire:target="back">
                    Memuat...
                </span>

            </button>

        </div>

    </div>

</div>