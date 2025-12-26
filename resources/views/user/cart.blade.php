<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <div class="max-w-5xl mx-auto p-6">
        <h2 class="text-3xl font-bold text-duarasa-red mb-6">🛒 Keranjang Belanja</h2>

        @if ($cartItems->count() > 0)
            <form action="" method="POST" id="cartForm">
                <table class="w-full border border-gray-300 rounded-lg">
                    <thead class="bg-duarasa-red text-white">
                        <tr>
                            <th class="p-3 text-left"><input type="checkbox" id="selectAll"></th>
                            <th class="p-3 text-left">Produk</th>
                            <th class="p-3 text-left">Harga</th>
                            <th class="p-3 text-center">Jumlah</th>
                            <th class="p-3 text-right">Subtotal</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                        @foreach ($cartItems as $item)
                            <tr class="border-t hover:bg-gray-100 transition">
                                <td class="p-3 text-center">
                                    <input type="checkbox" class="itemCheckbox" data-price="{{ $item->product->price }}"
                                        data-qty="{{ $item->quantity }}">
                                </td>
                                <td class="p-3 font-medium">{{ $item->product->name }}</td>
                                <td class="p-3">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                <td class="p-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" class="decrease px-2 py-1 bg-gray-200 rounded"
                                            data-id="{{ $item->id }}">−</button>
                                        <span id="qty-{{ $item->id }}">{{ $item->quantity }}</span>
                                        <button type="button" class="increase px-2 py-1 bg-gray-200 rounded"
                                            data-id="{{ $item->id }}">+</button>
                                    </div>
                                </td>
                                <td class="p-3 text-right font-semibold" id="subtotal-{{ $item->id }}">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-center">
                                    <form action="{{ route('user.cart.remove', $item->product_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6 flex justify-between items-center">
                    <div class="text-lg font-semibold">
                        Total: <span id="totalPrice">Rp 0</span>
                    </div>
                    <a href="{{ route('customer.checkout') }}"
                        class="bg-duarasa-red text-white px-6 py-2 rounded-lg hover:bg-duarasa-darkred transition">
                        Lanjut ke Checkout
                    </a>
                </div>
            </form>
        @else
            <p class="text-gray-500">Keranjang kamu masih kosong 😢</p>
        @endif
    </div>

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
            selectAll?.addEventListener('change', (e) => {
                checkboxes.forEach(cb => cb.checked = e.target.checked);
                updateTotal();
            });

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
