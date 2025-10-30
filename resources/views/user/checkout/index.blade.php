<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

  <!-- Navbar -->
  <nav class="bg-white shadow-md py-4">
    <div class="container mx-auto flex justify-between items-center px-4">
      <h1 class="text-2xl font-bold text-amber-700">CoffeeShop</h1>
      <div>
        <a href="/" class="text-gray-600 hover:text-amber-600">Home</a>
        <a href="/cart" class="ml-4 text-gray-600 hover:text-amber-600">Keranjang</a>
      </div>
    </div>
  </nav>

  <!-- Checkout Section -->
  <section class="container mx-auto px-4 py-10 grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Left: Produk -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
      <h2 class="text-xl font-semibold mb-4 border-b pb-2">Daftar Pesanan</h2>

      @forelse($cartItems as $item)
      <div class="flex justify-between items-center border-b py-3">
        <div class="flex items-center space-x-3">
          <img src="{{ asset('storage/' . $item->product->image) }}" class="w-16 h-16 rounded-lg object-cover" alt="Product Image">
          <div>
            <h3 class="font-semibold">{{ $item->product->name }}</h3>
            <p class="text-sm text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
          </div>
        </div>
        <p class="font-semibold text-amber-700">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
      </div>
      @empty
        <p class="text-center text-gray-500 py-4">Keranjang kamu masih kosong.</p>
      @endforelse
    </div>

    <!-- Right: Alamat & Ringkasan -->
    <div>
      <form action="{{ route('checkout.store') }}" method="POST" class="bg-white rounded-xl shadow p-6">
        @csrf
        <h2 class="text-xl font-semibold mb-4 border-b pb-2">Alamat Pengiriman</h2>

        @if($addresses->isEmpty())
          <p class="text-gray-500 mb-4">Belum ada alamat. Tambahkan alamat baru:</p>
          <a href="{{ route('address.create') }}" class="inline-block bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700">Tambah Alamat</a>
        @else
          <select name="address_id" class="w-full border-gray-300 rounded-lg mt-2 focus:ring-amber-500 focus:border-amber-500">
            <option value="">-- Pilih Alamat --</option>
            @foreach($addresses as $address)
              <option value="{{ $address->id }}">{{ $address->label }} - {{ $address->full_address }}</option>
            @endforeach
          </select>
        @endif

        <div class="mt-8 border-t pt-4">
          <h3 class="text-lg font-semibold mb-2">Ringkasan Pembayaran</h3>

          @php
            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
            $shipping = 10000;
            $total = $subtotal + $shipping;
          @endphp

          <div class="flex justify-between text-sm mb-1">
            <span>Subtotal</span>
            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <span>Ongkos Kirim</span>
            <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
          </div>
          <div class="flex justify-between font-semibold text-lg mt-2 border-t pt-2">
            <span>Total</span>
            <span class="text-amber-700">Rp {{ number_format($total, 0, ',', '.') }}</span>
          </div>

          <button type="submit" class="mt-6 w-full bg-amber-600 text-white py-3 rounded-lg hover:bg-amber-700 transition">
            Buat Pesanan
          </button>
        </div>
      </form>
    </div>
  </section>

</body>
</html>
