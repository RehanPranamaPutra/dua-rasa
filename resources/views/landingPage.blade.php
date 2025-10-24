@php
    // SIMULASI STATUS LOGIN (ganti true jika sudah login)
    $logged_in = false;

    // Data gambar slideshow
    $slides = [
        asset('asset/menu/dendeng.jpg'),
        asset('asset/menu/serundeng.jpg'),
        asset('asset/menu/rendang.jpg'),
    ];

    // Data menu statis (simulasi)
    $menuItems = [
        [
            'name' => 'Chicken Burger',
            'price' => 12000,
            'cal' => 150,
            'img' => 'https://via.placeholder.com/150?text=Burger',
        ],
        [
            'name' => 'Chicken Pizza',
            'price' => 15000,
            'cal' => 250,
            'img' => 'https://via.placeholder.com/150?text=Pizza',
        ],
        [
            'name' => 'Chicken Rice',
            'price' => 15000,
            'cal' => 250,
            'img' => 'https://via.placeholder.com/150?text=Rice',
        ],
        [
            'name' => 'Special Dessert',
            'price' => 5000,
            'cal' => 120,
            'img' => 'https://via.placeholder.com/150?text=Dessert',
        ],
    ];

    // Data testimoni
    $testimonials = [
        [
            'name' => 'Sarah P.',
            'review' => 'Rasa sambalnya otentik banget! Pengiriman cepat dan makanan selalu hangat!',
            'rating' => 5,
        ],
        [
            'name' => 'Ahmad R.',
            'review' => 'Kualitas bahan makanannya terasa premium. Ayamnya juicy banget!',
            'rating' => 4,
        ],
        [
            'name' => 'Fitriani',
            'review' => 'Pelayanannya ramah, dessert-nya juara! Pasti order lagi!',
            'rating' => 5,
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DuaRasa Kitchen</title>
    <link rel="icon" type="image/png" href="{{ asset('asset/logo/image.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .text-duarasa-red {
            color: #dc3545;
        }

        .bg-duarasa-red {
            background-color: #dc3545;
        }

        .hover\:bg-duarasa-darkred:hover {
            background-color: #b52d39;
        }

        .bg-duarasa-cream {
            background-color: #f8f8e7;
        }

        .slide-image {
            transition: opacity 1s ease-in-out;
        }

        .star-rating {
            color: #ffd700;
        }
    </style>
</head>

<body class="font-sans bg-white text-gray-800">

    {{-- 🌟 NAVBAR --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="#" class="flex items-center space-x-2">
                <img src="{{ asset('asset/logo/image.png') }}" alt="Logo" class="h-12 w-auto">
                <h1 class="font-extrabold text-2xl">
                    <span class="text-duarasa-red">DUARASA</span><span class="text-gray-500"> Kitchen</span>
                </h1>
            </a>

            <div class="hidden md:flex space-x-6">
                <a href="#menu" class="hover:text-duarasa-red">Menu</a>
                <a href="#why-us" class="hover:text-duarasa-red">Kenapa Kami?</a>
                <a href="#testimonials" class="hover:text-duarasa-red">Testimoni</a>
                <a href="#contact" class="hover:text-duarasa-red">Kontak</a>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                @if ($logged_in)
                    <a href="/cart" class="relative hover:text-duarasa-red">
                        🛒
                        <span class="absolute -top-2 -right-2 text-xs bg-duarasa-red text-white px-1.5 rounded-full">2</span>
                    </a>
                    <a href="/profile" class="hover:text-duarasa-red">👤</a>
                @else
                    <a href="/login"
                        class="bg-duarasa-red text-white px-4 py-2 rounded-lg hover:bg-duarasa-darkred transition">
                        Login / Register
                    </a>
                @endif
            </div>
        </div>
    </nav>

    {{-- 🌟 HERO SECTION --}}
    <section class="container mx-auto px-4 py-16 flex flex-col md:flex-row items-center justify-between">
        <div class="md:w-1/2">
            <h1 class="text-5xl font-extrabold leading-tight mb-4">
                JUST COME TO <br><span class="text-duarasa-red">DUARASA</span> & ORDER
            </h1>
            <p class="italic text-gray-600 mb-6">Setiap Rasa Punya Cerita</p>
            <p class="text-gray-700 mb-6">
                Nikmati makanan berkualitas terbaik, segar dari dapur kami langsung ke pintu rumah Anda!
            </p>
            <div class="space-x-3">
                <button
                    class="bg-duarasa-red text-white px-6 py-3 rounded-lg hover:bg-duarasa-darkred transition">Order
                    Now</button>
                <button
                    class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-100 transition">Explore
                    More</button>
            </div>
        </div>

        <div class="md:w-1/2 mt-10 md:mt-0 relative">
            <div id="hero-slider" class="relative w-full h-[400px] overflow-hidden rounded-lg">
                @foreach ($slides as $i => $slide)
                    <img src="{{ $slide }}" class="slide-image absolute inset-0 w-full h-full object-cover
                    {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
                @endforeach
            </div>
        </div>
    </section>

    {{-- 🌟 MENU SECTION --}}
    <section id="menu" class="text-center py-16 bg-duarasa-cream">
    <h2 class="text-3xl font-bold mb-10">OUR MENU</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
        @foreach ($menuItems as $menu)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="w-32 h-32 mx-auto mb-4 overflow-hidden rounded-full border-4 border-gray-100">
                    <img src="{{ $menu['img'] }}" alt="{{ $menu['name'] }}" class="w-full h-full object-cover">
                </div>
                <h3 class="font-semibold text-lg">{{ $menu['name'] }}</h3>
                <div class="text-sm text-gray-600 my-2">
                    <span class="font-bold text-duarasa-red">Rp{{ number_format($menu['price'], 0, ',', '.') }}</span>
                    <span>|</span> <span>{{ $menu['cal'] }} Cal</span>
                </div>

                {{-- Form tambah ke keranjang: kirim product_id lewat POST --}}
                <form action="{{ route('user.cart.store') }}" method="POST" class="inline">
                    @csrf
                    {{-- gunakan id jika ada, jika tidak gunakan loop index sebagai fallback --}}
                    <input type="hidden" name="product_id" value="{{ $menu['id'] ?? ($loop->index + 1) }}">
                    <button type="submit"
                        class="bg-duarasa-red text-white text-sm px-4 py-2 rounded-lg hover:bg-duarasa-darkred transition">
                        🛒 Tambah ke Keranjang
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</section>

    {{-- 🌟 WHY US --}}
    <section id="why-us" class="text-center py-16">
        <h2 class="text-3xl font-bold mb-10">WHY CHOOSE US?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <div class="p-6 bg-white shadow-lg rounded-lg">
                <div class="text-duarasa-red text-3xl mb-3">🥗</div>
                <h3 class="font-bold text-xl mb-2">Serve Healthy Food</h3>
                <p class="text-gray-600 text-sm">Kami menyajikan makanan sehat dengan bahan segar dan berkualitas.</p>
            </div>
            <div class="p-6 bg-white shadow-lg rounded-lg">
                <div class="text-duarasa-red text-3xl mb-3">🏅</div>
                <h3 class="font-bold text-xl mb-2">Best Quality</h3>
                <p class="text-gray-600 text-sm">Kualitas rasa dan bahan adalah prioritas utama kami.</p>
            </div>
            <div class="p-6 bg-white shadow-lg rounded-lg">
                <div class="text-duarasa-red text-3xl mb-3">🚚</div>
                <h3 class="font-bold text-xl mb-2">Fast Delivery</h3>
                <p class="text-gray-600 text-sm">Pesanan Anda kami antar cepat dan aman sampai tujuan.</p>
            </div>
        </div>
    </section>

    {{-- 🌟 TESTIMONIALS --}}
    <section id="testimonials" class="bg-duarasa-cream text-center py-16">
        <h2 class="text-3xl font-bold mb-10">APA KATA MEREKA?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach ($testimonials as $testi)
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="mb-3 text-xl star-rating">
                        @for ($i = 0; $i < $testi['rating']; $i++)
                            ⭐
                        @endfor
                    </div>
                    <p class="italic text-gray-700 mb-3">"{{ $testi['review'] }}"</p>
                    <p class="font-semibold text-gray-800">- {{ $testi['name'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- 🌟 FOOTER --}}
    <footer id="contact" class="bg-gray-800 text-white py-10">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 px-4">
            <div>
                <h3 class="text-duarasa-red text-2xl font-bold mb-2">DUARASA</h3>
                <p class="text-gray-400 text-sm">Setiap rasa punya cerita. Masakan terbaik tradisional & modern.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-3 border-b border-duarasa-red pb-1">Tautan</h4>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li><a href="#menu" class="hover:text-duarasa-red">Menu</a></li>
                    <li><a href="#why-us" class="hover:text-duarasa-red">Keunggulan</a></li>
                    <li><a href="#testimonials" class="hover:text-duarasa-red">Testimoni</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-3 border-b border-duarasa-red pb-1">Kontak</h4>
                <p class="text-gray-400 text-sm">📞 +62 812-3456-7890</p>
                <p class="text-gray-400 text-sm">📧 order@duarasa.com</p>
                <p class="text-gray-400 text-sm">📍 Kota Rasa, Jawa Barat</p>
            </div>
            <div>
                <h4 class="font-semibold mb-3 border-b border-duarasa-red pb-1">Jam Buka</h4>
                <p class="text-gray-400 text-sm">Setiap Hari: 10.00 - 22.00 WIB</p>
            </div>
        </div>
        <div class="text-center text-gray-500 text-sm mt-6">
            © {{ date('Y') }} DuaRasa Kitchen. All rights reserved.
        </div>
    </footer>

    {{-- 🌟 SLIDESHOW SCRIPT --}}
    <script>
        const slides = document.querySelectorAll('.slide-image');
        let index = 0;
        setInterval(() => {
            slides[index].classList.remove('opacity-100');
            slides[index].classList.add('opacity-0');
            index = (index + 1) % slides.length;
            slides[index].classList.remove('opacity-0');
            slides[index].classList.add('opacity-100');
        }, 3500);
    </script>
</body>
</html>
