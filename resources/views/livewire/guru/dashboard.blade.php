<div class="space-y-8 p-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Guru</h1>
        <p class="text-sm text-gray-500">
            Ringkasan aktivitas dan peminjaman inventaris
        </p>
    </div>


   <!-- STAT CARD -->
<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

    <!-- TOTAL BARANG -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 
                text-white p-4 sm:p-6 rounded-2xl shadow-lg 
                hover:scale-105 transition duration-300">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-xs sm:text-sm opacity-90">
                    Total Barang
                </p>

                <h2 class="text-xl sm:text-3xl font-bold mt-1 sm:mt-2">
                    {{ $totalBarang }}
                </h2>
            </div>

            <div class="bg-white/20 p-2 sm:p-3 rounded-xl">
                <x-heroicon-o-cube class="w-5 h-5 sm:w-7 sm:h-7"/>
            </div>

        </div>

    </div>



    <!-- SEDANG DIPINJAM -->
    <div class="bg-gradient-to-r from-orange-400 to-orange-500 
                text-white p-4 sm:p-6 rounded-2xl shadow-lg 
                hover:scale-105 transition duration-300">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-xs sm:text-sm opacity-90">
                    Sedang Dipinjam
                </p>

                <h2 class="text-xl sm:text-3xl font-bold mt-1 sm:mt-2">
                    {{ $sedangDipinjam }}
                </h2>
            </div>

            <div class="bg-white/20 p-2 sm:p-3 rounded-xl">
                <x-heroicon-o-arrow-up-tray class="w-5 h-5 sm:w-7 sm:h-7"/>
            </div>

        </div>

    </div>



    <!-- PENGAJUAN PENDING -->
    <div class="bg-gradient-to-r from-red-500 to-red-600 
                text-white p-4 sm:p-6 rounded-2xl shadow-lg 
                hover:scale-105 transition duration-300">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-xs sm:text-sm opacity-90">
                    Pengajuan Pending
                </p>

                <h2 class="text-xl sm:text-3xl font-bold mt-1 sm:mt-2">
                    {{ $pending }}
                </h2>
            </div>

            <div class="bg-white/20 p-2 sm:p-3 rounded-xl">
                <x-heroicon-o-clock class="w-5 h-5 sm:w-7 sm:h-7"/>
            </div>

        </div>

    </div>

</div>



    <!-- RINGKASAN KETERSEDIAAN BARANG -->
<div class="bg-white rounded-xl shadow p-6 mt-8">
    <h3 class="font-semibold text-gray-800 mb-4">
        Ringkasan Ketersediaan Barang
    </h3>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Nama Barang</th>
                    <th class="px-4 py-3 text-left">Kategori</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topBarang as $barang)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium">
                            {{ $barang->nama_barang }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $barang->kategori ?? '-' }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $barang->lokasi ?? '-' }}
                        </td>
                        <td class="px-4 py-3 font-semibold">
                            {{ $barang->jumlah_total }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                            Belum ada data barang
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



    <!-- GRID BAWAH -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

       <!-- PEMINJAM AKTIF -->
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold text-gray-800 mb-4">
        Peminjam Aktif
    </h3>

    <!-- Scrollable -->
    <div class="max-h-60 overflow-y-auto pr-2">
        <ul class="space-y-3 text-sm text-gray-600">
            @forelse($peminjamAktif as $item)
                <li class="flex justify-between items-center">
                    <span>
                        {{ $item->peminjaman->user->name }}
                        -
                        {{ $item->barang->nama_barang }}
                        ({{ $item->jumlah }})
                    </span>

                    <span class="text-orange-500 font-medium">
                        Dipinjam
                    </span>
                </li>
            @empty
                <li class="text-gray-400">
                    Tidak ada peminjam aktif
                </li>
            @endforelse
        </ul>
    </div>
</div>


        <!-- NOTIFIKASI STATUS -->
<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-semibold text-gray-800 mb-4">
        Notifikasi Pengajuan
    </h3>

    <div class="space-y-3 text-sm">
        @forelse($notifikasiPengajuan as $pengajuan)

            @php
                $barang = $pengajuan->detailBarang->first()?->barang?->nama_barang ?? 'Barang';
            @endphp

            @if($pengajuan->status == 'Menunggu')
                <div class="p-3 rounded-lg bg-yellow-100 text-yellow-700">
                    Pengajuan {{ $barang }} menunggu persetujuan
                </div>

            @elseif($pengajuan->status == 'Disetujui')
                <div class="p-3 rounded-lg bg-green-100 text-green-700">
                    Pengajuan {{ $barang }} disetujui
                </div>

            @elseif($pengajuan->status == 'Ditolak')
                <div class="p-3 rounded-lg bg-red-100 text-red-700">
                    Pengajuan {{ $barang }} ditolak
                </div>
            @endif

        @empty
            <div class="text-gray-400">
                Tidak ada notifikasi terbaru
            </div>
        @endforelse
    </div>
</div>


    </div>

</div>
