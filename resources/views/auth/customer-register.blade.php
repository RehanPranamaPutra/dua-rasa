<x-guest-layout>
    <h2 class="text-2xl font-bold mb-4 text-center">Register Customer</h2>

    <form method="POST" action="{{ route('customer.register') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" type="text" name="name" required />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" required />
        </div>

        <div>
            <x-input-label for="no_hp" value="No HP" />
            <x-text-input id="no_hp" type="text" name="no_hp" required />
        </div>

        <div>
            <x-input-label for="password" value="Password" />
            <x-text-input id="password" type="password" name="password" required />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required />
        </div>

        <x-primary-button class="w-full">Daftar</x-primary-button>
    </form>
</x-guest-layout>
