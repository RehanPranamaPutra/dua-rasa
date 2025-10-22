<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>DuaRasa | Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animasi halus */
        * {
            transition: all 0.25s ease;
        }

        /* Efek glass untuk sidebar */
        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Efek hover menu */
        .nav-link:hover {
            background-color: rgba(59,130,246,0.1);
            color: #2563eb;
            transform: translateX(4px);
        }

        /* Item aktif */
        .active-link {
            background-color: rgba(59,130,246,0.15);
            color: #1d4ed8;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 text-gray-800 font-sans flex min-h-screen">

    <!-- Sidebar -->
    <aside class="glass w-64 p-6 flex flex-col justify-between shadow-lg">
        <div>
            <div class="flex items-center gap-3 mb-10">
                <div class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded-xl font-bold text-lg shadow-md">
                    DR
                </div>
                <h1 class="text-2xl font-bold text-blue-700">DuaRasa</h1>
            </div>

            <nav class="flex flex-col space-y-2">
                <a href="{{ route('user.dashboard') }}"
                   class="nav-link block px-4 py-2 rounded-lg {{ request()->routeIs('user.dashboard') ? 'active-link' : '' }}">
                    🏠 Dashboard
                </a>

                <a href="{{ route('user.products') }}"
                   class="nav-link block px-4 py-2 rounded-lg {{ request()->routeIs('user.products') ? 'active-link' : '' }}">
                    🛍️ Produk
                </a>

                <a href="{{ route('user.cart.index') }}"
                   class="nav-link block px-4 py-2 rounded-lg {{ request()->routeIs('user.cart.*') ? 'active-link' : '' }}">
                    🛒 Keranjang
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="nav-link block px-4 py-2 rounded-lg {{ request()->routeIs('profile.edit') ? 'active-link' : '' }}">
                    👤 Profil
                </a>
            </nav>
        </div>

        <!-- Logout -->
        <form action="{{ route('customer.logout') }}" method="POST" class="mt-8">
            @csrf
            <button type="submit"
                class="w-full px-4 py-2 bg-red-100 text-red-600 font-semibold rounded-lg hover:bg-red-200 hover:text-red-700 transition flex items-center justify-center gap-2">
                🚪 Logout
            </button>
        </form>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            @yield('content')
        </div>
    </main>

</body>
</html>
