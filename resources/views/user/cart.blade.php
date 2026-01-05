<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - DuaRasa Kitchen</title>
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

        .border-duarasa-red {
            border-color: #dc3545;
        }
    </style>
</head>

<body class="font-sans bg-gray-50 text-gray-800">

    {{-- 🌟 NAVBAR --}}
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('landing-page') }}" class="flex items-center space-x-2">
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
                <a href="{{ route('user.cart.index') }}" class="relative hover:text-duarasa-red">
                    🛒
                    <span
                        class="absolute -top-2 -right-2 text-xs bg-duarasa-red text-white px-1.5 rounded-full">{{ $cartItems->count() }}</span>
                </a>
                <a href="/profile" class="hover:text-duarasa-red">👤</a>
            </div>
        </div>
    </nav>

    {{-- 🌟 HEADER --}}
    <section class="bg-duarasa-cream py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-duarasa-red mb-4">🛒 Keranjang Belanja</h1>
            <p class="text-gray-600">Kelola item di keranjang Anda sebelum checkout.</p>
        </div>
    </section>

    {{-- 🌟 CART CONTENT --}}
    <section class="py-12">
        <div class="container mx-auto px-4 max-w-6xl">
            @if ($cartItems->count() > 0)
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="border-b-2 border-duarasa-red">
                                <tr class="text-left">
                                    <th class="p-4"><input type="checkbox" id="selectAll"
                                            class="w-4 h-4 text-duarasa-red border-gray-300 rounded focus:ring-duarasa-red">
                                    </th>
                                    <th class="p-4 font-semibold text-duarasa-red">Produk</th>
                                    <th class="p-4 font-semibold text-duarasa-red">Harga</th>
                                    <th class="p-4 font-semibold text-duarasa-red text-center">Jumlah</th>
                                    <th class="p-4 font-semibold text-duarasa-red text-right">Subtotal</th>
                                    <th class="p-4 font-semibold text-duarasa-red text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cartBody">
                                @foreach ($cartItems as $item)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="p-4">
                                            <input type="checkbox"
                                                class="itemCheckbox w-4 h-4 text-duarasa-red border-gray-300 rounded focus:ring-duarasa-red"
                                                data-price="{{ $item->product->price }}"
                                                data-qty="{{ $item->amount }}">
                                        </td>
                                        <td class="p-4">
                                            <div class="flex items-center space-x-4">
                                                <img src="{{ asset('storage/' . $item->product->image) }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="w-16 h-16 object-cover rounded-lg">
                                                <div>
                                                    <h3 class="font-semibold text-gray-800">{{ $item->product->name }}
                                                    </h3>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 font-medium">Rp
                                            {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <button type="button"
                                                    class="decrease w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-600 hover:text-gray-800 transition"
                                                    data-id="{{ $item->id }}">−</button>
                                                <span id="qty-{{ $item->id }}"
                                                    class="w-8 text-center">{{ $item->amount }}</span>
                                                <button type="button"
                                                    class="increase w-8 h-8 bg-gray-200 hover:bg-gray-300 rounded-full flex items-center justify-center text-gray-600 hover:text-gray-800 transition"
                                                    data-id="{{ $item->id }}">+</button>
                                            </div>
                                        </td>
                                        <td class="p-4 text-right font-semibold text-duarasa-red"
                                            id="subtotal-{{ $item->id }}">
                                            Rp {{ number_format($item->product->price * $item->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="p-4 text-center">
                                            <form action="{{ route('user.cart.remove', $item->product_id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <div class="text-lg font-semibold">
                            Total: <span id="totalPrice" class="text-duarasa-red text-xl font-bold">Rp
                                {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="space-x-4">
                            <a href="{{ route('landing-page') }}"
                                class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                                Lanjut Belanja
                            </a>
                            <a href="{{ route('customer.order') }}"
                                class="bg-duarasa-red text-white px-6 py-2 rounded-lg hover:bg-duarasa-darkred transition">
                                Lanjut ke Checkout
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <div class="text-6xl mb-4">🛒</div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Keranjang Kosong</h2>
                    <p class="text-gray-600 mb-6">Belum ada produk di keranjang Anda. Yuk mulai belanja!</p>
                    <a href="{{ route('landing-page') }}"
                        class="bg-duarasa-red text-white px-6 py-3 rounded-lg hover:bg-duarasa-darkred transition">
                        Mulai Belanja
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- 🌟 FOOTER --}}
    <footer class="bg-gray-800 text-white py-10 mt-12">
        <div class="container mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 px-4">
            <div>
                <h3 class="text-duarasa-red text-2xl font-bold mb-2">DUARASA</h3>
                <p class="text-gray-400 text-sm">Setiap rasa punya cerita. Masakan terbaik tradisional & modern.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-3 border-b border-duarasa-red pb-1">Tautan</h4>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li><a href="{{ route('landing-page') }}#menu" class="hover:text-duarasa-red">Menu</a></li>
                    <li><a href="{{ route('landing-page') }}#why-us" class="hover:text-duarasa-red">Keunggulan</a>
                    </li>
                    <li><a href="{{ route('landing-page') }}#testimonials"
                            class="hover:text-duarasa-red">Testimoni</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-3 border-b border-duarasa-red pb-1">Kontak</h4>
                <p class="text-gray-400 text-sm">📞 +62 812-3456-7890</p>
                <p class="text-gray-400 text-sm">📧 order@duarasa.com</p>
                <p class="text-gray-400 text-sm">📍 Kota Rasa, Jawa Barat</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const checkboxes = document.querySelectorAll('.itemCheckbox');
            const selectAll = document.getElementById('selectAll');
            const totalPriceEl = document.getElementById('totalPrice');

            // Hitung total harga saat checkbox berubah
            function updateTotal() {
                let total = 0;
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const price = parseInt(cb.dataset.price);
                        const qty = parseInt(cb.dataset.qty);
                        total += price * qty;
                    }
                });
                totalPriceEl.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
            if (selectAll) {
                selectAll.addEventListener('change', (e) => {
                    checkboxes.forEach(cb => cb.checked = e.target.checked);
                    updateTotal();
                });
            }

            // Tambah/kurangi jumlah produk
            document.querySelectorAll('.increase').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const qtyEl = document.getElementById(`qty-${id}`);
                    let qty = parseInt(qtyEl.textContent);
                    qty++;
                    qtyEl.textContent = qty;

                    const checkbox = btn.closest('tr').querySelector('.itemCheckbox');
                    checkbox.dataset.qty = qty;

                    const price = parseInt(checkbox.dataset.price);
                    const subtotalEl = document.getElementById(`subtotal-${id}`);
                    subtotalEl.textContent = `Rp ${(price * qty).toLocaleString('id-ID')}`;

                    updateTotal();
                });
            });

            document.querySelectorAll('.decrease').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    const qtyEl = document.getElementById(`qty-${id}`);
                    let qty = parseInt(qtyEl.textContent);
                    if (qty > 1) qty--;
                    qtyEl.textContent = qty;

                    const checkbox = btn.closest('tr').querySelector('.itemCheckbox');
                    checkbox.dataset.qty = qty;

                    const price = parseInt(checkbox.dataset.price);
                    const subtotalEl = document.getElementById(`subtotal-${id}`);
                    subtotalEl.textContent = `Rp ${(price * qty).toLocaleString('id-ID')}`;

                    updateTotal();
                });
            });
        });
    </script>

</body>

</html>
