

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
        /* Sembunyikan scrollbar di semua browser */
.no-scrollbar::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Edge */
}
.no-scrollbar {
    -ms-overflow-style: none; /* Internet Explorer */
    scrollbar-width: none; /* Firefox */
}
    </style>
        @yield('styles')
</head>

<body class="font-sans bg-white text-gray-800">

    {{-- 🌟 NAVBAR --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{route('filament.admin.auth.login')}}" class="flex items-center space-x-2">
                <img src="{{ asset('asset/logo/image.png') }}" alt="Logo" class="h-12 w-auto">
                <h1 class="font-extrabold text-2xl">
                    <span class="text-duarasa-red">DUARASA</span><span class="text-gray-500"> Kitchen</span>
                </h1>
            </a>

            <div class="hidden md:flex space-x-6">
                    <a href="{{ route('landing-page') }}#menu" class="hover:text-duarasa-red">Menu</a>
                    <a href="{{ route('landing-page') }}#why-us" class="hover:text-duarasa-red">Kenapa Kami?</a>
                    <a href="{{ route('landing-page') }}#testimonials" class="hover:text-duarasa-red">Testimoni</a>
                    <a href="{{ route('landing-page') }}#contact" class="hover:text-duarasa-red">Kontak</a>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                @if (auth('customer')->check())
                    <a href="/cart" class="relative hover:text-duarasa-red">
                        🛒
                        <span class="absolute -top-2 -right-2 text-xs bg-duarasa-red text-white px-1.5 rounded-full">2</span>
                    </a>
                    <a href="/profile" class="hover:text-duarasa-red">👤</a>
                @else
                    <a href="{{route('customer.login')}}"
                        class="bg-duarasa-red text-white px-4 py-2 rounded-lg hover:bg-duarasa-darkred transition">
                        Login / Register
                    </a>
                @endif
            </div>
        </div>
    </nav>

    {{-- 📄 CONTENT --}}
    @yield('content')

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
                <p class="text-gray-400 text-sm">📍 Kota Padang, Sumatera Barat</p>
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
        @yield('scripts')
</body>
</html>


