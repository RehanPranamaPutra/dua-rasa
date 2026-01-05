<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer - Dua Rasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        /* Warna Dua Rasa */
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

        .focus-duarasa-red:focus {
            ring-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-bg-duarasa-cream via-white to-bg-duarasa-cream min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">

            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-duarasa-red mb-2">Dua Rasa</h1>
                <p class="text-gray-500 text-sm">Masuk ke akun Anda</p>
            </div>

            <!-- Card Login -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">

                <!-- Header -->
                <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-circle text-duarasa-red"></i>
                    Login Customer
                </h2>

                <!-- Flash Message Success -->
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg flex items-start gap-3">
                        <i class="fas fa-check-circle mt-0.5 text-green-500"></i>
                        <div>
                            <p class="font-semibold">Berhasil!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Flash Message Error -->
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-duarasa-red text-duarasa-red p-4 mb-6 rounded-lg flex items-start gap-3">
                        <i class="fas fa-exclamation-circle mt-0.5 text-duarasa-red"></i>
                        <div>
                            <p class="font-semibold">Error!</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-duarasa-red text-duarasa-red p-4 mb-6 rounded-lg">
                        <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                        <ul class="list-disc ml-5 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('customer.login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope text-gray-400 mr-1"></i>
                            Email
                        </label>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="contoh@email.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus-duarasa-red focus:border-duarasa-red transition-all @error('email') border-duarasa-red @enderror"
                        />
                        @error('email')
                            <p class="text-duarasa-red text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Password
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                placeholder="Masukkan password Anda"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus-duarasa-red focus:border-duarasa-red transition-all @error('password') border-duarasa-red @enderror"
                            />
                            <button
                                type="button"
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-duarasa-red text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-duarasa-red border-gray-300 rounded focus:ring-duarasa-red">
                            <span class="text-gray-600">Ingat Saya</span>
                        </label>
                        <a href="#" class="text-duarasa-red hover:bg-duarasa-darkred font-medium">
                            Lupa Password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button
                        type="submit"
                        class="w-full bg-duarasa-red hover:bg-duarasa-darkred text-white font-bold py-3.5 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl group"
                    >
                        <span>Login</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">atau</span>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-gray-600 text-sm">
                            Belum punya akun?
                            <a href="{{ route('customer.register') }}" class="text-duarasa-red hover:bg-duarasa-darkred font-bold hover:underline">
                                Daftar Sekarang
                            </a>
                        </p>
                    </div>
                </form>

            </div>

            <!-- Footer -->
            <p class="text-center text-gray-400 text-sm mt-8">
                <i class="fas fa-shield-alt mr-1"></i>
                &copy; {{ date('Y') }} Dua Rasa. All rights reserved.
            </p>

            <!-- Back to Home -->
            <div class="text-center mt-4">
                <a href="{{ route('landing-page') }}" class="text-sm text-gray-500 hover:text-duarasa-red inline-flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>
