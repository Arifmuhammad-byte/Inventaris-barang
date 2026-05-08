<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Inventaris' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[#EBF4F6]">

<div class="min-h-screen flex">

   <!-- SIDEBAR -->
<aside class="w-64 h-screen bg-[#09637E] text-white fixed left-0 top-0 flex flex-col">

    <!-- LOGO -->
    <div class="px-6 py-4 text-lg font-bold border-b border-white/20">
        Admin Inventaris
    </div>

    <!-- NAVIGATION -->
    <nav class="flex-1 flex flex-col justify-between px-3 py-4 text-sm">

        <!-- BAGIAN ATAS -->
        <div class="space-y-4">

            <!-- MENU UTAMA -->
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-wider text-white/60 px-2">
                    Menu Utama
                </p>

                <a href="{{ route('admin.dashboard') }}"
                   wire:navigate
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                   {{ request()->routeIs('admin.dashboard') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">
                    <x-heroicon-o-home class="w-5 h-5"/>
                    Dashboard
                </a>
            </div>

            <!-- DATA MASTER -->
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-wider text-white/60 px-2">
                    Data Master
                </p>

                <a href="{{ route('admin.barang') }}"
                   wire:navigate
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                   {{ request()->routeIs('admin.barang*') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">
                    <x-heroicon-o-archive-box class="w-5 h-5"/>
                    Inventaris Barang
                </a>

                <a href="{{ route('admin.kategori-lokasi') }}"
                   wire:navigate
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                   {{ request()->routeIs('admin.kategori-lokasi') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">
                    <x-heroicon-o-tag class="w-5 h-5"/>
                    Kategori & Lokasi
                </a>
            </div>

            <!-- OPERASIONAL -->
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-wider text-white/60 px-2">
                    Operasional
                </p>

                <a href="{{ route('admin.peminjaman') }}"
                   wire:navigate
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                   {{ request()->routeIs('admin.peminjaman') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">
                    <x-heroicon-o-arrow-up-tray class="w-5 h-5"/>
                    Pemakaian
                </a>

                <a href="{{ route('admin.pengembalian') }}"
                   wire:navigate
                   class="flex items-center gap-2 px-3 py-2 rounded-lg transition
                   {{ request()->routeIs('admin.pengembalian') ? 'bg-[#088395]' : 'hover:bg-[#088395]' }}">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5"/>
                    Pengembalian
                </a>
            </div>

            <!-- LAPORAN -->
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-wider text-white/60 px-2">
                    Laporan & Sistem
                </p>

                <a href="{{ route('admin.laporan')}}"
                   wire:navigate
                   class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#088395] transition">
                    <x-heroicon-o-chart-bar class="w-5 h-5"/>
                    Laporan
                </a>

                <a href="{{ route('manajemen.pengguna')}}"
                   wire:navigate
                   class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#088395] transition">
                    <x-heroicon-o-users class="w-5 h-5"/>
                    Manajemen Pengguna
                </a>
            </div>

        </div>

        <!-- BAGIAN BAWAH -->
        <div class="space-y-1 border-t border-white/20 pt-4">

            <a href="{{ route('admin.profil') }}"
               wire:navigate
               class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#088395]">
                <x-heroicon-o-user-circle class="w-5 h-5"/>
                Profil
            </a>

           <a href="{{ route('logout') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[#088395]">

    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5"/>

    Log out
</a>

        </div>

    </nav>

</aside>
    <!-- MAIN CONTENT -->
<div class="flex-1 ml-64 flex flex-col min-h-screen">

    <!-- HEADER (STICKY) -->
    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="px-8 py-4 flex justify-between items-center">

            <div>
                <h1 class="text-lg font-semibold text-gray-800">
                    {{ $title ?? 'Dashboard Admin' }}
                </h1>
                <p class="text-sm text-gray-500">
                    Sistem Informasi Inventaris Sekolah
                </p>
            </div>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">
                    Halo, {{ auth()->user()->name ?? 'Admin' }}
                </span>

                <div class="w-9 h-9 rounded-full bg-[#09637E] text-white flex items-center justify-center font-semibold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A',0,1)) }}
                </div>
            </div>

        </div>
    </header>

    <!-- CONTENT (SCROLLABLE) -->
    <main class="flex-1 p-8 overflow-y-auto">
        {{ $slot }}
    </main>

</div>


@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('livewire:init', () => {

    Livewire.on('swal', (data) => {

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: data.icon,
            title: data.title,
            text: data.text,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });

    });

});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
