<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Customer - Dua Rasa</title>
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
                <p class="text-gray-500 text-sm">Buat akun baru Anda</p>
            </div>

            <!-- Card Register -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">

                <!-- Header -->
                <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-plus text-duarasa-red"></i>
                    Register Customer
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
                <form method="POST" action="{{ route('customer.register') }}" class="space-y-5" id="registerForm">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-gray-400 mr-1"></i>
                            Nama Lengkap
                        </label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Masukkan nama lengkap"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus-duarasa-red focus:border-duarasa-red transition-all @error('name') border-duarasa-red @enderror"
                        />
                        @error('name')
                            <p class="text-duarasa-red text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
                            placeholder="contoh@email.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus-duarasa-red focus:border-duarasa-red transition-all @error('email') border-duarasa-red @enderror"
                        />
                        @error('email')
                            <p class="text-duarasa-red text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone text-gray-400 mr-1"></i>
                            No HP
                        </label>
                        <input
                            id="no_hp"
                            type="tel"
                            name="no_hp"
                            value="{{ old('no_hp') }}"
                            required
                            placeholder="08123456789"
                            pattern="[0-9]{10,13}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus-duarasa-red focus:border-duarasa-red transition-all @error('no_hp') border-duarasa-red @enderror"
                        />
                        <p class="text-xs text-gray-500 mt-1">Format: 08xxxxxxxxxx (10-13 digit)</p>
                        @error('no_hp')
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
                                placeholder="Minimal 8 karakter"
                                minlength="8"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus-duarasa-red focus:border-duarasa-red transition-all @error('password') border-duarasa-red @enderror"
                            />
                            <button
                                type="button"
                                onclick="togglePassword('password', 'toggleIcon1')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        @error('password')
                            <p class="text-duarasa-red text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-1"></i>
                            Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                placeholder="Ulangi password"
                                minlength="8"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus-duarasa-red focus:border-duarasa-red transition-all"
                            />
                            <button
                                type="button"
                                onclick="togglePassword('password_confirmation', 'toggleIcon2')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                            >
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="flex items-start gap-2">
                        <input
                            type="checkbox"
                            id="terms"
                            name="terms"
                            required
                            class="mt-1 w-4 h-4 text-duarasa-red border-gray-300 rounded focus:ring-duarasa-red"
                        />
                        <label for="terms" class="text-sm text-gray-600">
                            Saya setuju dengan <a href="#" class="text-duarasa-red hover:underline font-medium">Syarat & Ketentuan</a>
                            serta <a href="#" class="text-duarasa-red hover:underline font-medium">Kebijakan Privasi</a>
                        </label>
                    </div>

                    <!-- Register Button -->
                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full bg-duarasa-red hover:bg-duarasa-darkred text-white font-bold py-3.5 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl group"
                    >
                        <span>Daftar Sekarang</span>
                        <i class="fas fa-user-plus group-hover:scale-110 transition-transform"></i>
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

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-gray-600 text-sm">
                            Sudah punya akun?
                            <a href="{{ route('customer.login') }}" class="text-duarasa-red hover:text-duarasa-darkred font-bold hover:underline">
                                Login Sekarang
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
        // Toggle Password Visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

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

        // Password Strength Checker
        const passwordInput = document.getElementById('password');
        const strengthIndicator = document.getElementById('password-strength');

        passwordInput.addEventListener('input', function() {
            const password = this.value;

            if (password.length > 0) {
                strengthIndicator.classList.remove('hidden');

                let strength = 0;
                const bars = [
                    document.getElementById('strength-bar-1'),
                    document.getElementById('strength-bar-2'),
                    document.getElementById('strength-bar-3'),
                    document.getElementById('strength-bar-4')
                ];

                // Reset bars
                bars.forEach(bar => {
                    bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500');
                    bar.classList.add('bg-gray-200');
                });

                // Check strength
                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;

                // Update bars
                const strengthLabel = document.getElementById('strength-label');
                const strengthText = document.getElementById('strength-text');

                if (strength === 1) {
                    bars[0].classList.replace('bg-gray-200', 'bg-duarasa-red');
                    strengthLabel.textContent = 'Lemah';
                    strengthText.classList.remove('text-yellow-600', 'text-blue-600', 'text-green-600');
                    strengthText.classList.add('text-duarasa-red');
                } else if (strength === 2) {
                    bars[0].classList.replace('bg-gray-200', 'bg-yellow-500');
                    bars[1].classList.replace('bg-gray-200', 'bg-yellow-500');
                    strengthLabel.textContent = 'Sedang';
                    strengthText.classList.remove('text-duarasa-red', 'text-blue-600', 'text-green-600');
                    strengthText.classList.add('text-yellow-600');
                } else if (strength === 3) {
                    bars[0].classList.replace('bg-gray-200', 'bg-blue-500');
                    bars[1].classList.replace('bg-gray-200', 'bg-blue-500');
                    bars[2].classList.replace('bg-gray-200', 'bg-blue-500');
                    strengthLabel.textContent = 'Baik';
                    strengthText.classList.remove('text-duarasa-red', 'text-yellow-600', 'text-green-600');
                    strengthText.classList.add('text-blue-600');
                } else if (strength === 4) {
                    bars.forEach(bar => bar.classList.replace('bg-gray-200', 'bg-green-500'));
                    strengthLabel.textContent = 'Sangat Kuat';
                    strengthText.classList.remove('text-duarasa-red', 'text-yellow-600', 'text-blue-600');
                    strengthText.classList.add('text-green-600');
                }
            } else {
                strengthIndicator.classList.add('hidden');
            }
        });

        // Form Submit Handler
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mendaftar...';
        });

        // Phone Number Validation
        document.getElementById('no_hp').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>

</body>

</html>
