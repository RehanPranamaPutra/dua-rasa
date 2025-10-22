@extends('user.layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-blue-600">Keranjang Belanja</h2>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($cartItems->isEmpty())
            <p class="text-gray-500 text-center py-10">Keranjang kamu masih kosong 😅</p>
        @else
            <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-blue-100">
                    <tr>
                        <th class="p-3 text-left">Produk</th>
                        <th class="p-3 text-left">Harga</th>
                        <th class="p-3 text-center">Jumlah</th>
                        <th class="p-3 text-right">Total</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr class="border-t">
                            <td class="p-3">{{ $item->product->name }}</td>
                            <td class="p-3">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">{{ $item->quantity }}</td>
                            <td class="p-3 text-right">
                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('user.cart.remove', $item->product_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirmDelete(event, '{{ $item->product->name }}')"
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 text-right">
                <span class="font-semibold text-lg">Total Keseluruhan: </span>
                <span class="text-xl font-bold text-blue-600">
                    Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 0, ',', '.') }}
                </span>
            </div>
        @endif
    </div>

    <script>
        function confirmDelete(event, productName) {
            event.preventDefault();
            if (confirm(`Yakin ingin menghapus produk "${productName}" dari keranjang?`)) {
                event.target.closest('form').submit();
            }
        }
    </script>
@endsection
