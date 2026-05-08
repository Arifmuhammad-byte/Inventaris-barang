<div class="p-8 bg-gray-50 min-h-screen">

    <div class="bg-white shadow-sm border border-gray-200">

        {{-- Header --}}
        <div class="px-8 py-6 border-b border-gray-200 flex items-center justify-between">

            {{-- Judul --}}
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Data Kategori & Lokasi Barang
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Daftar barang berdasarkan kategori dan lokasi
                </p>
            </div>

            {{-- Search --}}
            <div class="relative w-80">
                <input type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Cari nama, kategori, atau lokasi..."
                    class="w-full px-4 py-2 text-sm border border-gray-300
                           focus:outline-none focus:ring-2 focus:ring-[#09637E]
                           focus:border-[#09637E] transition">

                {{-- Spinner --}}
                <div wire:loading wire:target="search"
                    class="absolute right-3 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-4 w-4 text-[#09637E]"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </div>
            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm text-gray-700">

                {{-- Head --}}
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-8 py-4 text-left font-semibold">No</th>
                        <th class="px-8 py-4 text-left font-semibold">Nama Barang</th>
                        <th class="px-8 py-4 text-left font-semibold">Kategori</th>
                        <th class="px-8 py-4 text-left font-semibold">Lokasi</th>
                    </tr>
                </thead>

                {{-- Body --}}
                <tbody class="divide-y divide-gray-100">

                    @forelse($barangs as $index => $barang)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-8 py-4 text-gray-500">
                                {{ $barangs->firstItem() + $index }}
                            </td>

                            <td class="px-8 py-4 font-medium text-gray-900">
                                {{ $barang->nama_barang }}
                            </td>

                            <td class="px-8 py-4">
                                {{ $barang->kategori }}
                            </td>

                            <td class="px-8 py-4">
                                <span class="px-3 py-1 text-xs font-semibold border
                                    @switch($barang->lokasi)
                                        @case('Lab Fisika')
                                            bg-blue-50 text-blue-700 border-blue-200
                                            @break
                                        @case('Lab Kimia')
                                            bg-purple-50 text-purple-700 border-purple-200
                                            @break
                                        @case('Lab Biologi')
                                            bg-green-50 text-green-700 border-green-200
                                            @break
                                        @case('Gudang Olahraga')
                                            bg-red-50 text-red-700 border-red-200
                                            @break
                                        @case('Lab Komputer')
                                            bg-yellow-50 text-yellow-700 border-yellow-200
                                            @break
                                        @default
                                            bg-gray-50 text-gray-700 border-gray-200
                                    @endswitch
                                ">
                                    {{ $barang->lokasi ?? '-' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-10 text-gray-400">
                                Data barang belum tersedia
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        <div class="px-8 py-4 border-t border-gray-200">
             {{ $barangs->links('vendor.pagination.tailwind') }}
        </div>

    </div>

</div>
