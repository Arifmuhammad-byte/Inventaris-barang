<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-sm text-gray-500">Ringkasan data sistem inventaris</p>
    </div>

 <!-- CARD STATISTIK -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <!-- TOTAL BARANG -->
    <div class="relative bg-gradient-to-r from-blue-500 to-blue-600 
                text-white p-6 rounded-2xl shadow-lg hover:scale-105 
                transition transform duration-300">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm opacity-90">Jumlah Seluruh Barang</p>
                <h2 class="text-3xl font-bold mt-2">{{ $totalBarang ?? 0 }}</h2>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <x-heroicon-o-cube class="w-7 h-7"/>
            </div>
        </div>
    </div>

    <!-- KONDISI BAIK -->
    <div class="relative bg-gradient-to-r from-green-500 to-green-600 
                text-white p-6 rounded-2xl shadow-lg hover:scale-105 
                transition transform duration-300">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm opacity-90">Kondisi Baik</p>
                <h2 class="text-3xl font-bold mt-2">{{ $kondisiBaik ?? 0 }}</h2>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <x-heroicon-o-check-circle class="w-7 h-7"/>
            </div>
        </div>
    </div>

    <!-- RUSAK RINGAN -->
    <div class="relative bg-gradient-to-r from-yellow-400 to-yellow-500 
                text-white p-6 rounded-2xl shadow-lg hover:scale-105 
                transition transform duration-300">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm opacity-90">Rusak Ringan</p>
                <h2 class="text-3xl font-bold mt-2">{{ $rusakRingan ?? 0 }}</h2>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <x-heroicon-o-exclamation-circle class="w-7 h-7"/>
            </div>
        </div>
    </div>

    <!-- RUSAK BERAT -->
    <div class="relative bg-gradient-to-r from-red-500 to-red-600 
                text-white p-6 rounded-2xl shadow-lg hover:scale-105 
                transition transform duration-300">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm opacity-90">Rusak Berat</p>
                <h2 class="text-3xl font-bold mt-2">{{ $rusakBerat ?? 0 }}</h2>
            </div>
            <div class="bg-white/20 p-3 rounded-xl">
                <x-heroicon-o-x-circle class="w-7 h-7"/>
            </div>
        </div>
    </div>

</div>


    <!-- GRAFIK PEMINJAMAN -->
   <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

    <!-- Header -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-900">
            Peminjaman
        </h3>
        <p class="text-sm text-gray-500">
            15 Hari Terakhir
        </p>
    </div>

    <div class="h-[260px]">
        <canvas id="loanChart"></canvas>
    </div>

    @php
        $last = end($chartData);
        $first = $chartData[0] ?? 0;
        $percent = $first > 0 
            ? round((($last - $first) / $first) * 100, 1) 
            : 0;
    @endphp

    <div class="mt-6 text-sm">
        <span class="font-medium {{ $percent >= 0 ? 'text-emerald-600' : 'text-red-500' }}">
            {{ $percent >= 0 ? '▲' : '▼' }} {{ abs($percent) }}%
        </span>
        <span class="text-gray-500">
            dibanding 15 hari sebelumnya
        </span>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const ctx = document.getElementById('loanChart').getContext('2d');

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(59,130,246,0.4)');
    gradient.addColorStop(1, 'rgba(59,130,246,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                data: @json($chartData),
                borderColor: '#3b82f6',
                backgroundColor: gradient,
                fill: true,
                tension: 0.4, // bikin smooth wave
                borderWidth: 2,
                pointRadius: 0, // hilangin titik
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#9ca3af' }
                },
                y: {
                    grid: { color: '#f3f4f6' },
                    ticks: { color: '#9ca3af' }
                }
            }
        }
    });
});
</script>

    <!-- DAFTAR PEMINJAM AKTIF -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800 text-lg">Daftar Peminjam Aktif</h3>
            <span class="text-sm text-gray-500">Sedang dipinjam</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Nama Peminjam</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Barang</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Jumlah</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Tanggal Pinjam</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($peminjamAktif as $peminjaman)
                        @foreach($peminjaman->detailBarang as $detail)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">{{ $peminjaman->user->name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $detail->barang->nama_barang ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $detail->jumlah ?? 0 }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                                        @if($peminjaman->status == 'Menunggu') bg-yellow-100 text-yellow-600
                                        @elseif($peminjaman->status == 'Disetujui') bg-blue-100 text-blue-600
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ $peminjaman->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                                Belum ada peminjaman aktif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- NOTIFIKASI SISTEM -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Notifikasi Sistem</h3>
            <span class="text-sm text-gray-500">Terbaru</span>
        </div>

        <ul class="space-y-4 text-sm">
            <li class="flex items-start gap-3 p-4 rounded-lg bg-yellow-50">
                <div class="w-2 h-2 mt-2 rounded-full bg-yellow-500"></div>
                <div>
                    <p class="font-medium text-gray-800">Barang hampir habis</p>
                    <p class="text-gray-600">Stok spidol tersisa kurang dari 5 unit</p>
                    <span class="text-xs text-gray-400">5 menit lalu</span>
                </div>
            </li>
            <li class="flex items-start gap-3 p-4 rounded-lg bg-blue-50">
                <div class="w-2 h-2 mt-2 rounded-full bg-blue-500"></div>
                <div>
                    <p class="font-medium text-gray-800">Peminjaman baru</p>
                    <p class="text-gray-600">Guru B meminjam Proyektor</p>
                    <span class="text-xs text-gray-400">10 menit lalu</span>
                </div>
            </li>
            <li class="flex items-start gap-3 p-4 rounded-lg bg-green-50">
                <div class="w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                <div>
                    <p class="font-medium text-gray-800">Pengembalian berhasil</p>
                    <p class="text-gray-600">Bola basket telah dikembalikan</p>
                    <span class="text-xs text-gray-400">30 menit lalu</span>
                </div>
            </li>
            <li class="flex items-start gap-3 p-4 rounded-lg bg-red-50">
                <div class="w-2 h-2 mt-2 rounded-full bg-red-500"></div>
                <div>
                    <p class="font-medium text-gray-800">Barang rusak</p>
                    <p class="text-gray-600">Laptop Inventaris terdeteksi rusak berat</p>
                    <span class="text-xs text-gray-400">1 jam lalu</span>
                </div>
            </li>
        </ul>
    </div>

</div>
