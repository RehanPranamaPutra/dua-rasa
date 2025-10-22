<x-app-layout>
    <div class="p-6 text-gray-900">
        <h2 class="text-2xl font-bold">Selamat datang, {{ Auth::guard('customer')->user()->name }}!</h2>
        <p class="mt-2">Ini adalah dashboard pengguna e-commerce kamu 🎉</p>

        <form method="POST" action="{{ route('customer.logout') }}" class="mt-4">
            @csrf
            <x-primary-button>Logout</x-primary-button>
        </form>
    </div>
</x-app-layout>
