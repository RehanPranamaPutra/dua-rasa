@php
    // SIMULASI STATUS LOGIN: Ganti 'false' menjadi 'true' untuk melihat ikon Keranjang dan Profil
    $logged_in = false;

    // Definisikan daftar gambar untuk slideshow.
    // Pastikan Anda memiliki path asset yang valid di Laravel.
    $slides = [
        asset('asset/menu/dendeng.jpg'),
        // Ganti dengan path asset gambar Anda yang sebenarnya:
        asset('asset/menu/image.png'),
        asset('asset/menu/test3.jpg'),
        // Tambahkan foto lain sesuai kebutuhan
    ];

    // Data Testimoni
    $testimonials = [
        [
            'name' => 'Sarah P.',
            'review' =>
                'Rasa sambalnya otentik banget! Perpaduan masakan tradisional dan modernnya benar-benar unik. Pengiriman juga cepat!',
            'rating' => 5,
        ],
        [
            'name' => 'Ahmad R.',
            'review' =>
                'Kualitas bahan makanannya terasa premium. Ayamnya juicy dan porsinya pas. Wajib coba untuk makan siang!',
            'rating' => 4,
        ],
        [
            'name' => 'Fitriani',
            'review' =>
                'Pelayanannya ramah dan pesanan selalu datang tepat waktu. Dessert mereka juara! Sangat direkomendasikan.',
            'rating' => 5,
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DuaRasaKitchen</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo/image.png') }}" sizes="46x46" />

    {{-- Menggunakan Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* CSS Kustom untuk Skema Warna DUARASA */
        .text-duarasa-red {
            color: #DC3545;
            /* Merah cerah */
        }

        .bg-duarasa-red {
            background-color: #DC3545;
            /* Merah cerah untuk tombol */
        }

        .hover\:bg-duarasa-darkred:hover {
            background-color: #C5293A;
            /* Merah sedikit lebih gelap untuk hover */
        }

        .bg-duarasa-cream {
            background-color: #F8F8E7;
            /* Warna krem/putih tulang */
        }

        .border-duarasa-red {
            border-color: #DC3545;
        }

        .border-duarasa-lightcream {
            border-color: #EEEEDD;
            /* Krem muda untuk border menu item */
        }

        /* Transisi khusus untuk slideshow */
        .slide-image {
            transition: opacity 1000ms ease-in-out;
            /* Transisi 1 detik */
        }

        /* Style untuk Rating Bintang (menggunakan emoji bintang) */
        .star-rating {
            color: #FFD700;
            /* Warna emas/kuning */
        }
    </style>
</head>

<body class="font-sans bg-white">


    {{--  NAVBAR --}}

    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">

                {{-- ====================== LOGO / BRAND ====================== --}}
                <a href="#" class="flex items-center space-x-3 group">
                    <img src="{{ asset('asset/logo/image.png') }}" alt="Logo Duarasa"
                        class="h-14 w-auto transition-transform duration-300 group-hover:scale-110">
                    <div class="flex flex-col leading-tight">
                        <span class="text-3xl font-extrabold text-gray-800 font-serif tracking-tight">
                            <span class="text-duarasa-red">DUARASA</span>
                            <span class="text-2xl ml-1 text-gray-500">Kitchen</span>
                        </span>
                    </div>
                </a>

                {{-- ====================== NAVIGATION LINKS ====================== --}}
                <div class="hidden md:flex space-x-8">
                    <a href="#menu" class="text-gray-600 hover:text-duarasa-red font-medium transition duration-200">
                        Menu
                    </a>
                    <a href="#why-us" class="text-gray-600 hover:text-duarasa-red font-medium transition duration-200">
                        Kenapa Kami?
                    </a>
                    <a href="#testimonials"
                        class="text-gray-600 hover:text-duarasa-red font-medium transition duration-200">
                        Testimoni
                    </a>
                    <a href="#contact" class="text-gray-600 hover:text-duarasa-red font-medium transition duration-200">
                        Kontak
                    </a>
                </div>

                {{-- ====================== ACTION BUTTON ====================== --}}
                <div class="hidden md:flex items-center space-x-4">
                    @if ($logged_in)
                        {{-- Cart Icon --}}
                        <a href="#cart"
                            class="relative p-2 text-gray-600 hover:text-duarasa-red transition duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.2 7h15.4l-2.2-7H7zM15 21a1 1 0 11-2 0 1 1 0 012 0zM7 21a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                            <span
                                class="absolute top-0 right-0 inline-flex items-center justify-center
                                     px-2 py-1 text-xs font-bold text-white
                                     transform translate-x-1/2 -translate-y-1/2
                                     bg-duarasa-red rounded-full">
                                2
                            </span>
                        </a>

                        {{-- User Icon --}}
                        <a href="#profile" class="p-2 text-gray-600 hover:text-duarasa-red transition duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                    @else
                        {{-- Guest --}}
                        <a href="#login-modal"
                            class="px-4 py-2 text-sm bg-duarasa-red text-white font-semibold rounded-lg shadow-md
                              hover:bg-duarasa-darkred transition duration-300">
                            Login / Register
                        </a>
                    @endif
                </div>

                {{-- ====================== MOBILE MENU BUTTON ====================== --}}
                <button class="md:hidden text-gray-600 hover:text-duarasa-red focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- =================================== --}}


    <div class="min-h-screen relative overflow-hidden">
        {{-- Background dan elemen dekoratif diubah ke Krem --}}
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-0 right-0 w-32 h-32 bg-duarasa-cream transform rotate-45 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-duarasa-cream transform -rotate-45 opacity-50"></div>
        </div>

        {{-- Main Content Container --}}
        <main class="container mx-auto px-4 py-12 relative z-10">

            {{-- 1. Hero Section DENGAN SLIDESHOW KOTAK --}}
            <section class="flex flex-col md:flex-row items-center justify-between mb-16 pt-8">
                <div class="md:w-1/2 mb-8 md:mb-0">
                    <h1 class="text-6xl font-extrabold text-gray-800 leading-tight mb-4">
                        JUST COME TO <br>
                        <span class="text-duarasa-red font-serif">DUARASA</span> & <br>
                        ORDER
                    </h1>
                    <p class="text-gray-700 text-sm mb-6 italic font-serif">Setiap Rasa Punya Cerita</p>

                    <p class="text-gray-600 text-lg mb-8 max-w-sm">
                        Now You Will Find Best Quality and Fresh Food From Order to Your Doorstop. Let Give The Best
                        Service Us
                    </p>
                    <div class="flex space-x-4">
                        <button
                            class="px-6 py-3 bg-duarasa-red text-white font-semibold rounded-lg shadow-md hover:bg-duarasa-darkred transition duration-300">
                            Order Now
                        </button>
                        <button
                            class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-md hover:bg-gray-50 transition duration-300">
                            Explore More
                        </button>
                    </div>
                </div>

                {{-- 🖼️ SLIDESHOW IMAGE CONTAINER (Menyatu dengan background) --}}
                <div class="md:w-1/2 flex justify-center relative bg-white">
                    <div class="w-full max-w-xl overflow-hidden rounded-lg  bg-white p-0 m-0">
                        {{-- Tinggi kontainer fix --}}
                        <div id="hero-slider" class="relative w-full h-[400px] bg-white">
                            @foreach ($slides as $key => $slide)
                                <img src="{{ asset($slide) }}" alt="Menu Image {{ $key + 1 }}"
                                    class="slide-image absolute inset-0 w-full h-full object-cover 
                           transition-opacity duration-700 ease-in-out
                           {{ $key === 0 ? 'opacity-100' : 'opacity-0' }}">
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 🖼️ END SLIDESHOW IMAGE CONTAINER --}}
            </section>
        </main>

    </div>


    {{-- 🖼️ END SLIDESHOW CONTAINER --}}

    </section>

    <hr id="menu" class="my-10 border-gray-200">

    {{-- 2. Menu Items Section --}}
    <section class="text-center mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-10">OUR MENU</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">

            {{-- Menu Item 1: Chicken Burger --}}
            <div class="flex flex-col items-center p-4">
                <div class="w-32 h-32 rounded-full border-4 border-duarasa-lightcream mb-4 overflow-hidden">
                    <img src="https://via.placeholder.com/150?text=Burger" alt="Chicken Burger"
                        class="w-full h-full object-cover">
                </div>
                <h3 class="font-semibold text-lg mb-1">Chicken Burger</h3>
                <div class="flex space-x-2 text-sm text-gray-600 border border-gray-200 rounded-full py-1 px-3">
                    <span class="font-bold text-duarasa-red">$12.00</span>
                    <span>|</span>
                    <span>150 Cal</span>
                </div>
            </div>

            {{-- Menu Item 2: Chicken Pizza --}}
            <div class="flex flex-col items-center p-4">
                <div class="w-32 h-32 rounded-full border-4 border-duarasa-lightcream mb-4 overflow-hidden">
                    <img src="https://via.placeholder.com/150?text=Pizza" alt="Chicken Pizza"
                        class="w-full h-full object-cover">
                </div>
                <h3 class="font-semibold text-lg mb-1">Chicken Pizza</h3>
                <div class="flex space-x-2 text-sm text-gray-600 border border-gray-200 rounded-full py-1 px-3">
                    <span class="font-bold text-duarasa-red">$15.00</span>
                    <span>|</span>
                    <span>250 Cal</span>
                </div>
            </div>

            {{-- Menu Item 3: Chicken Rice --}}
            <div class="flex flex-col items-center p-4">
                <div class="w-32 h-32 rounded-full border-4 border-duarasa-lightcream mb-4 overflow-hidden">
                    <img src="https://via.placeholder.com/150?text=Rice" alt="Chicken Rice"
                        class="w-full h-full object-cover">
                </div>
                <h3 class="font-semibold text-lg mb-1">Chicken Rice</h3>
                <div class="flex space-x-2 text-sm text-gray-600 border border-gray-200 rounded-full py-1 px-3">
                    <span class="font-bold text-duarasa-red">$15.00</span>
                    <span>|</span>
                    <span>250 Cal</span>
                </div>
            </div>

            {{-- Menu Item 4: Special Dessert --}}
            <div class="flex flex-col items-center p-4">
                <div class="w-32 h-32 rounded-full border-4 border-duarasa-lightcream mb-4 overflow-hidden">
                    <img src="https://via.placeholder.com/150?text=Dessert" alt="Special Dessert"
                        class="w-full h-full object-cover">
                </div>
                <h3 class="font-semibold text-lg mb-1">Special Dessert</h3>
                <div class="flex space-x-2 text-sm text-gray-600 border border-gray-200 rounded-full py-1 px-3">
                    <span class="font-bold text-duarasa-red">$5.00</span>
                    <span>|</span>
                    <span>120 Cal</span>
                </div>
            </div>
        </div>
    </section>

    <hr id="why-us" class="my-10 border-gray-200">

    {{-- 3. Why Choose Us Section --}}
    <section class="text-center mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-10">WHY CHOOSE US?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- Feature 1: Serve Healthy Food --}}
            <div class="p-6 bg-white border border-gray-100 rounded-lg shadow-lg">
                <div class="mb-4 text-4xl text-duarasa-red flex justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-gray-800">Serve Healthy Food</h3>
                <p class="text-sm text-gray-600">
                    We Will Offer Healthy Food To Be Right For Client Fitness And Health
                </p>
            </div>

            {{-- Feature 2: Best Quality --}}
            <div class="p-6 bg-white border border-gray-100 rounded-lg shadow-lg">
                <div class="mb-4 text-4xl text-duarasa-red flex justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.132-2.059-.382-3.016z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-gray-800">Best Quality</h3>
                <p class="text-sm text-gray-600">
                    We Will Strive To Serve Best Quality Meals, With Celeverity Food Ingredients
                </p>
            </div>

            {{-- Feature 3: Fast Delivery (ICON KURIR VAN) --}}
            <div class="p-6 bg-white border border-gray-100 rounded-lg shadow-lg">
                <div class="mb-4 text-4xl text-duarasa-red flex justify-center">
                    {{-- Ikon Van Pengiriman yang Sederhana --}}
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 17H5a2 2 0 01-2-2V9a2 2 0 012-2h4M12 5V3a2 2 0 012-2h6a2 2 0 012 2v8m-3 3h3a2 2 0 002-2v-3a2 2 0 00-2-2h-3m-6 3h.01M16 19a2 2 0 11-4 0 2 2 0 014 0zM7 19a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-xl mb-2 text-gray-800">Fast Delivery</h3>
                <p class="text-sm text-gray-600">
                    We Can Deliver Food Immediately, We Make Sure The Team Can Do The
                </p>
            </div>
        </div>
    </section>

    <hr id="testimonials" class="my-10 border-gray-200">

    {{-- 4. ⭐ TESTIMONIALS SECTION (BARU) --}}
    <section class="text-center mb-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-10">APA KATA MEREKA?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ($testimonials as $testimonial)
                <div class="p-6 bg-duarasa-cream rounded-lg shadow-lg flex flex-col items-center">
                    {{-- Rating Bintang --}}
                    <div class="mb-3 text-xl star-rating">
                        {{-- Menampilkan rating bintang solid (⭐) sesuai nilai --}}
                        @for ($i = 0; $i < $testimonial['rating']; $i++)
                            ⭐
                        @endfor
                        {{-- Menampilkan bintang outline (☆) untuk rating yang kurang --}}
                        @for ($i = 0; $i < 5 - $testimonial['rating']; $i++)
                            <span class="text-gray-300">⭐</span>
                        @endfor
                    </div>
                    <p class="text-gray-700 italic mb-4">"{{ $testimonial['review'] }}"</p>
                    <p class="font-semibold text-gray-800">- {{ $testimonial['name'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <hr class="my-10 border-gray-200">

    {{-- 5. Call to Action Footer --}}
    <section class="text-center py-8 bg-duarasa-cream rounded-lg mb-12">
        <h2 class="text-2xl font-bold text-gray-800 tracking-wider">
            VISIT OUR SITE TO GET STARTED!
        </h2>
    </section>

    </main>

    {{-- =================================== --}}
    {{-- 📞 FOOTER DAN CONTACT INFO --}}
    {{-- =================================== --}}
    <footer id="contact" class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

                {{-- Kolom 1: Branding dan Deskripsi --}}
                <div>
                    <h3 class="text-3xl font-extrabold text-duarasa-red tracking-tight mb-2 font-serif">DUARASA
                    </h3>
                    <p class="text-gray-400 text-sm mb-4">Setiap Rasa Punya Cerita</p>
                    <p class="text-gray-400 text-sm">
                        Menyajikan masakan terbaik dengan kualitas bahan pilihan, menghadirkan dua rasa: Tradisional
                        dan Modern.
                    </p>
                </div>

                {{-- Kolom 2: Tautan Cepat --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b border-duarasa-red pb-1">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="/"
                                class="text-gray-400 hover:text-duarasa-red transition duration-200">Beranda</a>
                        </li>
                        <li><a href="#menu"
                                class="text-gray-400 hover:text-duarasa-red transition duration-200">Menu Kami</a>
                        </li>
                        <li><a href="#why-us"
                                class="text-gray-400 hover:text-duarasa-red transition duration-200">Keunggulan</a>
                        </li>
                        <li><a href="#testimonials"
                                class="text-gray-400 hover:text-duarasa-red transition duration-200">Testimoni</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-duarasa-red transition duration-200">FAQ</a></li>
                    </ul>
                </div>

                {{-- Kolom 3: Kontak dan Lokasi --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b border-duarasa-red pb-1">Hubungi Kami</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <span class="mr-2 text-duarasa-red">📞</span>
                            <p class="text-gray-400">+62 812-3456-7890</p>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-duarasa-red">📧</span>
                            <p class="text-gray-400">order@duarasakitchen.com</p>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-2 text-duarasa-red">📍</span>
                            <p class="text-gray-400">Jl. Kuliner No. 10, Kota Rasa, Jawa Barat</p>
                        </li>
                    </ul>
                </div>

                {{-- Kolom 4: Media Sosial dan Jam Operasional --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b border-duarasa-red pb-1">Ikuti Kami</h4>
                    <div class="flex space-x-3 text-2xl mb-4">
                        <a href="#" class="text-gray-400 hover:text-duarasa-red transition duration-200"
                            title="Instagram">📸</a>
                        <a href="#" class="text-gray-400 hover:text-duarasa-red transition duration-200"
                            title="Facebook">👍</a>
                        <a href="#" class="text-gray-400 hover:text-duarasa-red transition duration-200"
                            title="TikTok">🎵</a>
                    </div>

                    <h4 class="text-lg font-semibold mt-6 mb-2">Jam Buka</h4>
                    <p class="text-gray-400 text-sm">Setiap Hari: 10.00 - 22.00 WIB</p>
                </div>
            </div>
        </div>
        <div class="bg-gray-900 py-3">
            <div class="container mx-auto px-4 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} DuaRasa Kitchen. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    {{-- =================================== --}}

    </div>

    {{-- =================================== --}}
    {{-- ⚡ JAVASCRIPT SLIDESHOW LOGIC --}}
    {{-- =================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide-image');

            // Hentikan jika hanya ada satu slide atau kurang
            if (slides.length < 2) return;

            let currentSlide = 0;
            const slideInterval = 7000; // 7 detik tampilan
            const transitionDuration = 1000; // 1 detik transisi

            function nextSlide() {
                const prevSlide = slides[currentSlide];

                // 1. Pindah ke slide berikutnya (circular loop)
                currentSlide = (currentSlide + 1) % slides.length;
                const nextSlide = slides[currentSlide];

                // --- FASE CROSS-FADE (SALING SILANG) ---

                // A. Tampilkan slide berikutnya (NEXT) segera.
                nextSlide.classList.remove('hidden');

                // B. Mulai transisi: PREV fade-out, NEXT fade-in secara simultan.
                // PREV: opacity-100 -> opacity-0
                prevSlide.classList.remove('opacity-100');
                prevSlide.classList.add('opacity-0');

                // NEXT: opacity-0 -> opacity-100
                nextSlide.classList.remove('opacity-0');
                nextSlide.classList.add('opacity-100');

                // C. Bersihkan (Cleanup): Sembunyikan PREV slide HANYA setelah transisi selesai
                setTimeout(() => {
                    prevSlide.classList.add('hidden');
                }, transitionDuration);
            }

            // Jalankan slideshow secara otomatis
            setInterval(nextSlide, slideInterval);
        });
    </script>
</body>

</html>
