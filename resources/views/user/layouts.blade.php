<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md p-5 flex flex-col justify-between">
        <div>
            <h1 class="text-2xl font-bold text-blue-600 mb-6">DuaRasa</h1>
            <nav class="space-y-3">
                <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Dashboard</a>
                <a href="{{ route('user.products') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Produk</a>
                <a href="{{ route('user.cart.index') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Keranjang</a>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Profil</a>
            </nav>
        </div>
        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Konten utama -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</body>
</html>
