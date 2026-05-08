<div>

<!-- TOP BAR / SIDEBAR ATAS -->
<header class="bg-primary text-white sticky top-0 z-50 shadow">

<div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between relative">

    <!-- LOGO SAJA -->
    <div class="flex items-center gap-4">
        <img src="{{ asset('images/logosma3jadi.png') }}" 
             alt="Logo"
             class="w-10 h-10 md:w-14 md:h-14 object-contain">

        <h1 class="font-bold text-base md:text-xl">
            SMA N 3 OKU
        </h1>
    </div>

    <!-- CHECKBOX -->
<input type="checkbox" id="menu-toggle" class="hidden peer">

<!-- HAMBURGER -->
<label for="menu-toggle"
       class="md:hidden group flex flex-col justify-center items-center 
              w-8 h-8 cursor-pointer relative z-50">

    <!-- Garis Atas -->
    <span class="absolute w-6 h-0.5 bg-white 
                 transition-all duration-300
                 -translate-y-2
                 peer-checked:rotate-45
                 peer-checked:translate-y-0">
    </span>

    <!-- Garis Tengah -->
    <span class="absolute w-6 h-0.5 bg-white 
                 transition-all duration-300
                 peer-checked:opacity-0">
    </span>

    <!-- Garis Bawah -->
    <span class="absolute w-6 h-0.5 bg-white 
                 transition-all duration-300
                 translate-y-2
                 peer-checked:-rotate-45
                 peer-checked:translate-y-0">
    </span>

</label>

  <nav class="

    md:flex
    md:items-center md:gap-6

    absolute md:static
    top-full left-0 w-full md:w-auto

    bg-primary md:bg-transparent

    flex flex-col md:flex-row
    gap-4 md:gap-6

    p-4 md:p-0

    origin-top
    transition-all duration-300

    scale-y-0 opacity-0
    peer-checked:scale-y-100
    peer-checked:opacity-100

    md:scale-y-100 md:opacity-100
">

        <a href="#beranda" class="hover:text-background transition">
            Beranda
        </a>

        <a href="#barang" class="hover:text-background transition">
            Barang
        </a>

        <a href="#alur" class="hover:text-background transition">
            Alur Peminjaman
        </a>

        <a href="#keunggulan" class="hover:text-background transition">
            Keunggulan Sistem
        </a>

        <a wire:navigate 
           href="{{ route('pilih.role') }}"
           class="bg-white text-primary px-4 py-2 
                  rounded-lg font-semibold 
                  hover:bg-background transition text-center">
            Login
        </a>

    </nav>

</div>

</header>



   <!-- HERO -->
<section id="beranda" 
         class="relative h-[300px] sm:h-[400px] md:h-[500px] 
                overflow-hidden scroll-mt-24">

    <!-- SLIDER -->
    <div id="slider" 
         class="flex h-full transition-transform duration-700 ease-in-out">

        <img src="{{ asset('images/gambar lab.jpg') }}" 
             class="w-full h-full object-cover flex-shrink-0">

        <img src="{{ asset('images/bgolahraga.jpg') }}" 
             class="w-full h-full object-cover flex-shrink-0">

        <!-- CLONE FIRST IMAGE -->
        <img src="{{ asset('images/gambar lab.jpg') }}" 
             class="w-full h-full object-cover flex-shrink-0">

    </div>

    <!-- OVERLAY -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- CONTENT -->
    <div class="absolute inset-0 flex items-center justify-center"
         data-aos="fade-up"
         data-aos-duration="500">

        <div class="text-center text-white 
                    max-w-xl md:max-w-3xl 
                    px-4 sm:px-6">

            <h2 class="text-xl sm:text-2xl md:text-4xl 
                       font-bold mb-3 md:mb-4">

                Sistem Informasi Inventaris Barang

            </h2>

            <p class="text-sm sm:text-base md:text-lg 
                      text-gray-200">

                Platform digital untuk mengelola inventaris 
                laboratorium dan alat olahraga 
                SMA Negeri 3 OKU secara terpusat, 
                efisien, dan akurat.

            </p>

        </div>

    </div>

</section>



 <!-- BARANG -->
<section id="barang" 
         class="max-w-7xl mx-auto 
                px-4 sm:px-6 md:px-8 
                py-12 md:py-20">
    
    <!-- TITLE -->
    <div class="mb-8 md:mb-12 text-center">

        <h3 class="text-2xl sm:text-3xl 
                   font-bold text-primary">

            Inventaris Unggulan

        </h3>

        <p class="text-gray-400 
                  text-xs sm:text-sm 
                  mt-2">

            Data inventaris laboratorium 
            dan alat olahraga terbaru

        </p>

    </div>

    <!-- GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 
            gap-6 md:gap-10" 
     data-aos="fade-up">

@foreach([
    ['title'=>'Barang Laboratorium','color'=>'blue','data'=>$barangLab],
    ['title'=>'Alat Olahraga','color'=>'green','data'=>$alatOlahraga],
] as $section)

<div class="bg-white shadow-sm overflow-hidden flex flex-col rounded-lg">

    <!-- HEADER -->
    <div class="px-4 md:px-6 py-3 md:py-4 
                bg-{{ $section['color'] }}-50">

        <h4 class="font-semibold 
                   text-{{ $section['color'] }}-600 
                   text-base md:text-lg">

            {{ $section['title'] }}

        </h4>

    </div>

    <!-- TABLE -->
    <div>

        <table class="w-full text-xs md:text-sm">

            <thead class="bg-gray-50 text-gray-500">
                <tr>

                    <th class="text-left 
                               px-3 md:px-6 
                               py-2 md:py-3 
                               font-medium">

                        Nama Barang

                    </th>

                    <!-- Lokasi hidden mobile -->
                    <th class="hidden sm:table-cell 
                               text-left 
                               px-3 md:px-6 
                               py-2 md:py-3 
                               font-medium">

                        Lokasi

                    </th>

                    <th class="text-right 
                               px-3 md:px-6 
                               py-2 md:py-3 
                               font-medium">

                        Jumlah

                    </th>

                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">

                @forelse($section['data'] as $item)

                <tr class="hover:bg-gray-50 transition">

                    <!-- Nama + Lokasi mobile -->
                    <td class="px-3 md:px-6 
                               py-2 md:py-3 
                               font-medium text-gray-700">

                        {{ $item->nama_barang }}

                        <div class="text-gray-400 
                                    text-[11px] 
                                    sm:hidden">

                            {{ $item->lokasi }}

                        </div>

                    </td>

                    <!-- Lokasi desktop -->
                    <td class="hidden sm:table-cell 
                               px-3 md:px-6 
                               py-2 md:py-3 
                               text-gray-400">

                        {{ $item->lokasi }}

                    </td>

                    <td class="px-3 md:px-6 
                               py-2 md:py-3 
                               text-right 
                               font-semibold text-gray-600">

                        {{ $item->jumlah_total }}

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="3" 
                        class="text-center py-5 text-gray-400">

                        Tidak ada data

                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

        <!-- BUTTON AJUKAN -->
        <div class="px-4 md:px-6 py-3 md:py-4 
                    bg-gray-50 text-center">

            <a 
                wire:navigate
                href="{{ route('guru.login') }}"

                class="inline-flex items-center gap-2 
                       px-4 md:px-5 py-2 
                       text-xs md:text-sm 
                       font-medium text-white
                       bg-gradient-to-r 
                       from-[#093637] to-[#44a08d]
                       rounded-lg
                       hover:opacity-90
                       transition">

                <x-heroicon-o-plus class="w-4 h-4"/>

                Ajukan Pemakaian

            </a>

        </div>

    </div>

    <!-- BUTTON LIHAT -->
    <div class="px-4 md:px-6 py-3 md:py-4 
                bg-gray-50 text-center">

        <button 
            wire:click="showMore('{{ $loop->index == 0 ? 'lab' : 'olahraga' }}')"

            class="text-xs md:text-sm 
                   font-semibold 
                   text-{{ $section['color'] }}-600 
                   hover:underline">

            Lihat lebih banyak →

        </button>

    </div>

</div>

@endforeach

</div>
</section>

    <!-- Alur -->
<section id="alur" class="bg-gray-50">

<div class="max-w-7xl mx-auto 
            px-4 sm:px-6 md:px-6 
            py-12 md:py-20">

    <!-- TITLE -->
    <div class="text-center mb-10 md:mb-14">

        <h3 class="text-2xl md:text-3xl 
                   font-bold text-primary">

            Alur Peminjaman Barang

        </h3>

        <p class="text-gray-400 
                  text-xs md:text-sm 
                  mt-2">

            Proses peminjaman barang 
            yang mudah dan terstruktur

        </p>

    </div>

    <!-- TIMELINE -->
    <div class="relative" data-aos="fade-right">

        <!-- GARIS DESKTOP -->
        <div class="hidden md:block 
                    absolute top-6 left-0 
                    w-full h-0.5 bg-gray-200">
        </div>

        <!-- GARIS MOBILE -->
        <div class="md:hidden 
                    absolute left-6 top-0 
                    w-0.5 h-full bg-gray-200">
        </div>

        <div class="grid 
                    grid-cols-1 md:grid-cols-6 
                    gap-8 md:gap-8 relative">

            @foreach ([
                'Login ke sistem',
                'Pilih barang',
                'Ajukan peminjaman',
                'Verifikasi admin',
                'Barang digunakan',
                'Pengembalian'
            ] as $item)

            <div class="flex 
                        md:flex-col 
                        items-center 
                        md:text-center 
                        group relative">

                <!-- BULATAN -->
                <div class="w-12 h-12 
                            flex items-center justify-center 
                            rounded-full 
                            bg-gradient-to-r 
                            from-[#093637] to-[#44a08d]
                            text-white font-bold 
                            shadow-md
                            group-hover:scale-110 
                            transition
                            
                            md:mx-auto
                            z-10">

                    {{ $loop->iteration }}

                </div>

                <!-- CARD -->
                <div class="ml-4 md:ml-0 
                            mt-0 md:mt-4
                            bg-white 
                            px-4 py-3 
                            rounded-xl 
                            shadow-sm 
                            group-hover:shadow-md 
                            transition
                            w-full">

                    <p class="text-xs md:text-sm 
                              font-medium text-gray-700">

                        {{ $item }}

                    </p>

                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>

</section>

   <!-- KEUNGGULAN -->
<section id="keunggulan" class="bg-gray-50">

<div class="max-w-7xl mx-auto 
            px-4 sm:px-6 md:px-6 
            py-12 md:py-20">

    <!-- TITLE -->
    <div class="text-center mb-10 md:mb-14">

        <h3 class="text-2xl md:text-3xl 
                   font-bold text-primary">

            Keunggulan Sistem

        </h3>

        <p class="text-gray-400 
                  text-xs md:text-sm 
                  mt-2 
                  max-w-2xl mx-auto">

            Sistem dirancang untuk memberikan 
            kemudahan, ketepatan, dan efisiensi 
            dalam pengelolaan inventaris sekolah

        </p>

    </div>

    <!-- GRID -->
    <div class="grid 
                grid-cols-1 sm:grid-cols-2 md:grid-cols-4 
                gap-6 md:gap-8" 
         data-aos="zoom-in">

        @foreach ([
            ['title'=>'Terpusat','desc'=>'Seluruh data inventaris tersimpan dalam satu sistem yang terintegrasi sehingga mudah diakses dan dikelola.','icon'=>'database'],
            ['title'=>'Efisien','desc'=>'Proses pencatatan, peminjaman, dan pengembalian barang menjadi lebih cepat dan terorganisir.','icon'=>'bolt'],
            ['title'=>'Akurat','desc'=>'Mengurangi kesalahan pencatatan dengan sistem digital yang terstruktur dan terdokumentasi dengan baik.','icon'=>'check-circle'],
            ['title'=>'Laporan','desc'=>'Menyediakan laporan inventaris secara otomatis untuk memudahkan monitoring dan evaluasi.','icon'=>'document-text'],
        ] as $item)

        <div class="bg-white 
                    p-5 md:p-6 
                    rounded-2xl 
                    shadow-sm 
                    hover:shadow-lg 
                    transition 
                    group">

            <!-- ICON -->
            <div class="w-11 h-11 md:w-12 md:h-12 
                        flex items-center justify-center 
                        mb-4
                        rounded-xl
                        bg-gradient-to-r 
                        from-[#093637] to-[#44a08d]
                        text-white shadow-md
                        group-hover:scale-110 transition">

                @if($item['icon'] == 'database')
                    <x-heroicon-o-circle-stack class="w-5 h-5 md:w-6 md:h-6"/>
                @elseif($item['icon'] == 'bolt')
                    <x-heroicon-o-bolt class="w-5 h-5 md:w-6 md:h-6"/>
                @elseif($item['icon'] == 'check-circle')
                    <x-heroicon-o-check-circle class="w-5 h-5 md:w-6 md:h-6"/>
                @elseif($item['icon'] == 'document-text')
                    <x-heroicon-o-document-text class="w-5 h-5 md:w-6 md:h-6"/>
                @endif

            </div>

            <!-- TITLE -->
            <h4 class="font-semibold 
                       text-base md:text-lg 
                       text-gray-800 mb-2">

                {{ $item['title'] }}

            </h4>

            <!-- DESC -->
            <p class="text-xs md:text-sm 
                      text-gray-500 
                      leading-relaxed">

                {{ $item['desc'] }}

            </p>

        </div>

        @endforeach

    </div>

</div>

</section>

   <!-- FOOTER -->
<footer class="bg-primary text-white">

<div class="max-w-7xl mx-auto 
            px-4 sm:px-6 
            py-5 md:py-6 
            text-center">

    <p class="text-xs sm:text-sm leading-relaxed">

        © {{ date('Y') }}  
        Sistem Informasi Inventaris Barang  
        <br class="sm:hidden">
        SMA Negeri 3 OKU

    </p>

</div>

</footer>
@if($showModal)
<div class="fixed inset-0 z-50 flex items-center justify-center 
            bg-black/30 backdrop-blur-sm
            px-3 sm:px-4">

    <!-- CONTAINER -->
    <div class="bg-white 
                w-full 
                max-w-lg sm:max-w-3xl md:max-w-5xl
                shadow-xl
                max-h-[90vh]
                flex flex-col">

        <!-- HEADER -->
        <div class="flex justify-between items-center 
                    px-4 sm:px-6 
                    py-3 sm:py-4 
                    bg-gray-50">

            <h3 class="font-semibold 
                       text-base sm:text-lg 
                       text-gray-800">

                {{ $modalTitle }}

            </h3>

            <button wire:click="closeModal"
                class="text-gray-400 hover:text-gray-700 
                       text-lg sm:text-xl transition">

                ✕

            </button>

        </div>

        <!-- TABLE WRAPPER -->
        <div class="overflow-auto flex-1">

           <!-- TABLE -->
<div class="overflow-y-auto">

<table class="w-full text-xs sm:text-sm">

    <thead class="bg-white text-gray-500 sticky top-0 border-b">
        <tr>

            <th class="px-3 sm:px-6 py-2 sm:py-3 
                       text-left font-medium">

                Nama Barang

            </th>

            <!-- Lokasi disembunyikan di mobile -->
            <th class="hidden sm:table-cell 
                       px-3 sm:px-6 py-2 sm:py-3 
                       text-left font-medium">

                Lokasi

            </th>

            <th class="px-3 sm:px-6 py-2 sm:py-3 
                       text-right font-medium">

                Jumlah

            </th>

        </tr>
    </thead>

    <tbody class="divide-y divide-gray-100">

        @forelse($modalData as $item)

        <tr class="hover:bg-gray-50 transition">

            <!-- Nama Barang + Lokasi Mobile -->
            <td class="px-3 sm:px-6 py-2 sm:py-3 
                       font-medium text-gray-700">

                {{ $item->nama_barang }}

                <!-- Lokasi tampil di bawah saat mobile -->
                <div class="text-gray-400 text-[11px] sm:hidden">
                    {{ $item->lokasi }}
                </div>

            </td>

            <!-- Lokasi Desktop -->
            <td class="hidden sm:table-cell 
                       px-3 sm:px-6 py-2 sm:py-3 
                       text-gray-400">

                {{ $item->lokasi }}

            </td>

            <td class="px-3 sm:px-6 py-2 sm:py-3 
                       text-right font-semibold text-gray-600">

                {{ $item->jumlah_total }}

            </td>

        </tr>

        @empty

        <tr>
            <td colspan="3" 
                class="text-center py-6 text-gray-400">

                Tidak ada data

            </td>
        </tr>

        @endforelse

    </tbody>

</table>

</div>

        </div>

        <!-- FOOTER -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 
                    bg-gray-50 border-t text-right">

            <button 
                wire:click="closeModal"
                wire:loading.attr="disabled"
                wire:target="closeModal"

                class="inline-flex items-center gap-2
                       px-4 sm:px-5 py-2 
                       bg-primary text-white 
                       text-xs sm:text-sm 
                       font-semibold
                       rounded-lg shadow-sm
                       hover:opacity-90
                       active:scale-95
                       transition duration-200">

                <!-- Spinner -->
                <svg wire:loading wire:target="closeModal"
                    class="animate-spin h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">

                    <circle class="opacity-25"
                        cx="12" cy="12" r="10"
                        stroke="currentColor"
                        stroke-width="4"></circle>

                    <path class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v8H4z"></path>

                </svg>

                <span wire:loading.remove wire:target="closeModal">
                    Tutup
                </span>

                <span wire:loading wire:target="closeModal">
                    Menutup...
                </span>

            </button>

        </div>

    </div>

</div>
@endif
<!-- SCRIPT -->
<script>
    const slider = document.getElementById('slider');
    const slides = slider.children;
    const totalSlides = slides.length;

    let index = 0;

    setInterval(() => {
        index++;
        slider.style.transition = "transform 0.7s ease-in-out";
        slider.style.transform = `translateX(-${index * 100}%)`;

        // kalau sudah sampai clone terakhir
        if (index === totalSlides - 1) {
            setTimeout(() => {
                slider.style.transition = "none";
                slider.style.transform = "translateX(0)";
                index = 0;
            }, 700); // sesuai duration animasi
        }

    }, 3000);
</script>

</div>
