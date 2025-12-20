@extends('layouts.public')

@section('content')
    <section class="bg-[#faf9f4] min-h-screen py-20">
        <div class="max-w-7xl mx-auto bg-white rounded-3xl shadow-lg overflow-hidden flex flex-col md:flex-row">

            {{-- Gambar Produk --}}
            <div class="md:w-1/2 bg-gray-50 flex items-center justify-center p-10">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-full max-w-[550px] h-[550px] object-cover rounded-2xl shadow-md border border-gray-200">
            </div>

            {{-- Detail Produk --}}
            <div class="md:w-1/2 p-10 flex flex-col justify-between">

                {{-- Bagian Atas: Nama Produk --}}
                <div>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                        {{ $product->name }}
                    </h1>
                </div>

                {{-- Bagian Tengah: Harga, Deskripsi, dan Kuantitas --}}
                <div class="flex-1">
                    <p class="text-3xl text-[#d93b48] font-semibold mb-6">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <p class="text-gray-700 text-lg leading-relaxed mb-8">
                        {{ $product->description ?? 'Deskripsi produk belum tersedia.' }}
                    </p>
                    {{-- Pilihan Berat Produk --}}
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Berat / Ukuran</h3>

                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                            @php
                                // Misalnya data berat tersimpan sebagai string "250gr,500gr,1kg"
                                $weights = explode(',', $product->weight ?? '');
                            @endphp

                            @if (!empty($weights[0]))
                                @foreach ($weights as $weight)
                                    <button type="button"
                                        class="border border-gray-400 rounded-lg py-2 font-semibold text-gray-900 hover:border-[#d93b48] hover:text-[#d93b48] transition">
                                        {{ trim($weight) }}
                                    </button>
                                @endforeach
                            @else
                                <p class="text-gray-500 italic">Berat produk belum tersedia.</p>
                            @endif
                        </div>
                    </div>


                    {{-- Kuantitas --}}
                    <div class="mb-10">
                        <label for="quantity" class="block text-lg font-semibold mb-2">Kuantitas</label>
                        <select id="quantity" name="quantity"
                            class="w-32 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#d93b48] focus:border-[#d93b48]">
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Bagian Bawah: Tombol --}}
                <div class="flex flex-col md:flex-row gap-4">
                    {{-- Tombol Beli Sekarang --}}
                    <form action="#" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                            class="w-full border-2 border-black text-black font-bold py-4 rounded-lg text-lg uppercase hover:bg-black hover:text-white transition-all duration-300">
                            Beli Sekarang
                        </button>
                    </form>

                    {{-- Tombol Tambah ke Keranjang --}}
                    <form action="{{ route('user.cart.store') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit"
                            class="w-full bg-gray-300 text-white font-bold py-4 rounded-lg text-lg uppercase hover:bg-[#d93b48] transition-all duration-300">
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>

      {{-- Produk Lainnya --}}
@if (!empty($otherProducts) && $otherProducts->count() > 0)
    <div class="max-w-7xl mx-auto mt-20 px-4 relative">

        <h2 class="text-2xl font-bold text-gray-800 mb-6">Produk Lainnya</h2>

        {{-- Tombol Panah Kiri --}}
        <button id="scrollLeft"
            class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow-md hover:bg-gray-100 border border-gray-300 p-2 rounded-full z-10">
            &#10094;
        </button>

        {{-- Tombol Panah Kanan --}}
        <button id="scrollRight"
            class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow-md hover:bg-gray-100 border border-gray-300 p-2 rounded-full z-10">
            &#10095;
        </button>

        {{-- Scroll Container --}}
        <div id="productContainer"
            class="flex gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar">
            @foreach ($otherProducts as $item)
                <a href="{{ route('product.detail', $item->id) }}"
                    class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition transform hover:-translate-y-1 duration-300 p-4 border border-gray-200 min-w-[23%] snap-start flex-shrink-0">

                    {{-- Gambar produk --}}
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}"
                        class="w-full h-52 object-cover rounded-md mb-3">

                    {{-- Nama produk --}}
                    <h3 class="font-semibold text-gray-800 text-base leading-snug line-clamp-2 h-12 uppercase">
                        {{ $item->name }}
                    </h3>

                    {{-- Harga --}}
                    <p class="text-[#d93b48] font-extrabold text-lg mt-1">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </p>
                </a>
            @endforeach
        </div>
    </div>

    <script>
        const container = document.getElementById('productContainer');
        const btnLeft = document.getElementById('scrollLeft');
        const btnRight = document.getElementById('scrollRight');
        const itemWidth = container.querySelector('a').offsetWidth + 20; // geser per 1 produk

        btnLeft.addEventListener('click', () => {
            container.scrollBy({ left: -itemWidth, behavior: 'smooth' });
        });

        btnRight.addEventListener('click', () => {
            container.scrollBy({ left: itemWidth, behavior: 'smooth' });
        });

        // Auto scroll setiap 3 detik
        let autoScroll = setInterval(() => {
            // Jika sudah sampai ujung kanan, kembali ke awal
            if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 10) {
                container.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: itemWidth, behavior: 'smooth' });
            }
        }, 3000);

        // Berhenti auto-scroll ketika mouse di atas container
        container.addEventListener('mouseenter', () => clearInterval(autoScroll));
        // Lanjut lagi saat mouse keluar
        container.addEventListener('mouseleave', () => {
            autoScroll = setInterval(() => {
                if (container.scrollLeft + container.clientWidth >= container.scrollWidth - 10) {
                    container.scrollTo({ left: 0, behavior: 'smooth' });
                } else {
                    container.scrollBy({ left: itemWidth, behavior: 'smooth' });
                }
            }, 6000);
        });
    </script>
@endif


    </section>
@endsection
