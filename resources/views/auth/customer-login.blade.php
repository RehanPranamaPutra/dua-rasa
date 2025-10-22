<x-guest-layout>
    <h2 class="text-2xl font-bold mb-4 text-center">Login Customer</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('customer.login') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" required autofocus />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required />
        </div>

        <div class="flex justify-between items-center">
            <x-primary-button>Login</x-primary-button>
            <a href="{{ route('customer.register') }}" class="text-sm text-indigo-600 hover:underline">Belum punya akun?</a>
        </div>
    </form>
</x-guest-layout>
