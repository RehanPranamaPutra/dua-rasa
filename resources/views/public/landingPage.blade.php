@php
    // SIMULASI STATUS LOGIN (ganti true jika sudah login)
    $logged_in = false;

    // Data gambar slideshow
    $slides = [asset('asset/menu/dendeng.jpg'), asset('asset/menu/serundeng.jpg'), asset('asset/menu/rendang.jpg')];

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

@extends('layouts.public')

@section('content')
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
                <button class="bg-duarasa-red text-white px-6 py-3 rounded-lg hover:bg-duarasa-darkred transition">Order
                    Now</button>
                <button
                    class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-100 transition">Explore
                    More</button>
            </div>
        </div>

        <div class="md:w-1/2 mt-10 md:mt-0 relative">
            <div id="hero-slider" class="relative w-full h-[400px] overflow-hidden rounded-lg">
                @foreach ($slides as $i => $slide)
                    <img src="{{ $slide }}"
                        class="slide-image absolute inset-0 w-full h-full object-cover
                    {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
                @endforeach
            </div>
        </div>
    </section>

    {{-- 🌟 MENU SECTION --}}
    <section id="menu" class="text-center py-16 bg-duarasa-cream">
        <h2 class="text-3xl font-bold mb-10">OUR MENU</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
            @foreach ($products as $product)
                <div
                    class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 p-5 flex flex-col items-center">
                    {{-- Gambar Produk --}}
                    <a href="{{ route('product.detail', $product->id) }}" class="block w-full">
                        <div class="w-32 h-32 mx-auto mb-4 overflow-hidden rounded-full border-4 border-gray-100 shadow-sm">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover">
                        </div>

                        {{-- Nama & Harga --}}
                        <h3 class="font-semibold text-lg text-gray-800 mb-1">{{ $product->name }}</h3>
                        <p class="font-bold text-duarasa-red mb-3">
                            Rp{{ number_format($product->price, 0, ',', '.') }}
                        </p>
                    </a>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-col sm:flex-row gap-2 mt-auto w-full">
                        {{-- Tombol Beli Sekarang --}}
                        <a href="{{ route('product.detail', $product->id) }}"
                            class="w-full border-2 border-duarasa-red text-duarasa-red font-semibold px-4 py-2 rounded-lg hover:bg-duarasa-red hover:text-white transition duration-300 flex items-center justify-center gap-2">
                            🛍️ <span></span>
                        </a>

                        {{-- Tombol Tambah ke Keranjang --}}
                        <form action="{{ route('user.cart.store') }}" method="POST" class="w-full">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit"
                                class="w-full bg-duarasa-red text-white font-semibold px-4 py-2 rounded-lg hover:bg-duarasa-darkred transition duration-300 flex items-center justify-center gap-2">
                                🛒 <span></span>
                            </button>
                        </form>
                    </div>
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
@endsection
