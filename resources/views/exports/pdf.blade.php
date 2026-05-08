<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan {{ $type }}</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {
            button {
                display: none;
            }
        }

        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body class="p-6 text-gray-800">

    <!-- HEADER -->
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold uppercase">
            Laporan {{ $type }}
        </h1>
        <p class="text-sm text-gray-600">
            Periode: {{ $awal }} s/d {{ $akhir }}
        </p>
    </div>

    <!-- BUTTON PRINT -->
    <div class="mb-4 flex justify-end">
        <button onclick="window.print()"
                class="px-4 py-2 bg-blue-600 text-white text-sm hover:bg-blue-700">
            Cetak / Simpan PDF
        </button>
    </div>

    <!-- TABLE -->
    <table class="w-full border border-gray-400 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">No</th>

                @if($type === 'Peminjaman')
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Barang</th>
                    <th class="border px-3 py-2">Jumlah</th>
                    <th class="border px-3 py-2">Tanggal</th>
                @elseif($type === 'Inventaris')
                    <th class="border px-3 py-2">Nama Barang</th>
                    <th class="border px-3 py-2">Kategori</th>
                    <th class="border px-3 py-2">Lokasi</th>
                @else
                    <th class="border px-3 py-2">Nama Barang</th>
                    <th class="border px-3 py-2">Total</th>
                    <th class="border px-3 py-2">Baik</th>
                    <th class="border px-3 py-2">Rusak Ringan</th>
                    <th class="border px-3 py-2">Rusak Berat</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse($data as $i => $d)
            <tr class="hover:bg-gray-50">

                <td class="border px-3 py-2 text-center">
                    {{ $i + 1 }}
                </td>

                @if($type === 'Peminjaman')
                    <td class="border px-3 py-2">
                        {{ $d->peminjaman->user->name ?? '-' }}
                    </td>
                    <td class="border px-3 py-2">
                        {{ $d->barang->nama_barang ?? '-' }}
                    </td>
                    <td class="border px-3 py-2 text-center">
                        {{ $d->jumlah ?? 0 }}
                    </td>
                    <td class="border px-3 py-2">
                        {{ \Carbon\Carbon::parse($d->peminjaman->tanggal_pinjam)->format('d M Y') }}
                    </td>

                @elseif($type === 'Inventaris')
                    <td class="border px-3 py-2">
                        {{ $d->nama_barang }}
                    </td>
                    <td class="border px-3 py-2">
                        {{ $d->kategori }}
                    </td>
                    <td class="border px-3 py-2">
                        {{ $d->lokasi }}
                    </td>

                @else
                    <td class="border px-3 py-2">
                        {{ $d->nama_barang }}
                    </td>
                    <td class="border px-3 py-2 text-center">
                        {{ $d->jumlah_total }}
                    </td>
                    <td class="border px-3 py-2 text-center">
                        {{ $d->kondisi_baik }}
                    </td>
                    <td class="border px-3 py-2 text-center">
                        {{ $d->kondisi_rusak_ringan }}
                    </td>
                    <td class="border px-3 py-2 text-center">
                        {{ $d->kondisi_rusak_berat }}
                    </td>
                @endif

            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-gray-500">
                    Tidak ada data
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER TTD -->
    <div class="mt-10 flex justify-end">
        <div class="text-center">
            <p class="text-sm">Mengetahui,</p>
            <p class="mt-12 font-semibold">Admin</p>
        </div>
    </div>

    <!-- AUTO PRINT -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>
</html>