<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body 
    class="bg-[#EBF4F6] overflow-x-hidden"
    x-data="{ openSidebar:false }">

<div class="min-h-screen flex items-start">

    <!-- MOBILE OVERLAY -->
    <div 
        x-show="openSidebar"
        x-transition
        @click="openSidebar=false"
        class="fixed inset-0 bg-black/40 z-40 lg:hidden">
    </div>


    <!-- SIDEBAR -->
    <aside 
        class="w-64 h-screen bg-[#09637E] text-white fixed top-0 left-0 flex flex-col z-50
               transform transition-transform duration-300
               lg:translate-x-0"
        :class="openSidebar ? 'translate-x-0' : '-translate-x-full'">

        <!-- LOGO -->
        <div class="px-6 py-5 text-xl font-bold border-b border-white/20 flex justify-between items-center">

            Guru Inventaris

            <!-- CLOSE BUTTON (mobile only) -->
            <button 
                @click="openSidebar=false"
                class="lg:hidden">

                <x-heroicon-o-x-mark class="w-6 h-6"/>
            </button>

        </div>


        <!-- MENU NAV -->
        <nav class="flex-1 mt-6 px-4 text-sm overflow-y-auto space-y-4">

            <!-- MENU UTAMA -->
            <div class="space-y-1">

                <p class="text-xs uppercase tracking-wider text-white/60 px-4">
                    Menu Utama
                </p>

                <a href="{{ route('guru.dashboard') }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                   {{ request()->routeIs('guru.dashboard') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">

                    <x-heroicon-o-home class="w-5 h-5"/>

                    Dashboard
                </a>

            </div>


            <!-- DATA INVENTARIS -->
            <div class="space-y-3">

                <p class="text-xs uppercase tracking-wider text-white/60 px-4">
                    Data Inventaris
                </p>

                <a href="{{ route('guru.inventaris') }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                   {{ request()->routeIs('guru.inventaris') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">

                    <x-heroicon-o-archive-box class="w-5 h-5"/>

                    Inventaris Barang
                </a>

            </div>


            <!-- OPERASIONAL -->
            <div class="space-y-3">

                <p class="text-xs uppercase tracking-wider text-white/60 px-4">
                    Operasional
                </p>

                <a href="{{ route('guru.peminjaman') }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                   {{ request()->routeIs('guru.peminjaman') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">

                    <x-heroicon-o-arrow-up-tray class="w-5 h-5"/>

                    Pemakaian
                </a>

                <a href="{{ route('guru.pengembalian') }}"
                   wire:navigate
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition
                   {{ request()->routeIs('guru.pengembalian') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">

                    <x-heroicon-o-arrow-down-tray class="w-5 h-5"/>

                    Pengembalian
                </a>

            </div>

        </nav>


        <!-- BOTTOM -->
        <div class="px-4 py-4 border-t border-white/20 space-y-1">

            <a href="{{ route('guru.profil') }}"
               wire:navigate
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#088395]">

                <x-heroicon-o-user-circle class="w-5 h-5"/>

                Profil
            </a>

            <a href="{{ route('logout') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-[#088395]">

                <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5"/>

                Log out
            </a>

        </div>

    </aside>



    <!-- MAIN CONTENT -->
    <main class="flex-1 lg:ml-64 min-h-screen w-full">

        <!-- HEADER -->
        <div class="bg-white shadow-sm sticky top-0 z-30">

            <div class="px-6 lg:px-8 py-4 flex justify-between items-center">

                <div class="flex items-center gap-3">

                    <!-- HAMBURGER (mobile) -->
                    <button 
                        @click="openSidebar=true"
                        class="lg:hidden">

                        <x-heroicon-o-bars-3 class="w-7 h-7 text-gray-700"/>
                    </button>

                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">
                            Dashboard Guru
                        </h1>

                        <p class="text-sm text-gray-500">
                            Sistem Informasi Inventaris Sekolah
                        </p>
                    </div>

                </div>


                <!-- USER -->
                <div class="flex items-center gap-4">

                    <span class="hidden sm:block text-sm text-gray-600">
                        Halo, {{ auth()->user()->name ?? 'Guru' }}
                    </span>

                    <div class="w-9 h-9 rounded-full bg-[#09637E] text-white flex items-center justify-center font-semibold">

                        {{ strtoupper(substr(auth()->user()->name ?? 'G',0,1)) }}

                    </div>

                </div>

            </div>

        </div>


        <!-- CONTENT -->
        <div class="p-4 sm:p-6 lg:p-8 pb-20">
          {{ $slot }}
       </div>

    </main>

</div>

@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('swal-success', (data) => {
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                confirmButtonColor: '#09637E'
            });
        });
    });
</script>
</body>
</html>
