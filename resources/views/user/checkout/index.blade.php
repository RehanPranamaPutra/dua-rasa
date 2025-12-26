<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout - Dua Rasa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: { brand: { 500: '#f59e0b', 600: '#d97706', 700: '#b45309' } }
        }
      }
    }
  </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

  <!-- Navbar -->
  <nav class="bg-white border-b py-4 shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 flex justify-between items-center">
      <a href="/" class="text-2xl font-bold text-brand-700 tracking-tight">Dua Rasa</a>
      <div class="text-sm font-medium text-gray-500">Halaman Checkout</div>
    </div>
  </nav>

  <!-- Main Form -->
  <form action="{{ route('customer.order.store') }}" method="POST">
    @csrf

    <div class="container mx-auto px-4 py-8">

      <!-- Alert Error Bawaan Laravel -->
      @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">Perhatian!</p>
            <ul class="list-disc ml-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
      @endif

      @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">{{ session('error') }}</div>
      @endif

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- KOLOM KIRI: ALAMAT & ITEM -->
        <div class="lg:col-span-8 space-y-6">

          <!-- Pilih Alamat -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h2 class="font-bold text-gray-800 flex items-center">
                  <i class="fas fa-map-marker-alt text-brand-600 mr-2"></i> Alamat Pengiriman
                </h2>
            </div>
            <div class="p-6">
                @if($addresses->isEmpty())
                  <div class="text-center py-6 text-gray-500">
                    <p class="mb-3">Belum ada alamat tersimpan.</p>
                    <a href="#" class="text-brand-600 font-bold hover:underline">Tambah Alamat Baru</a>
                  </div>
                @else
                  <div class="grid grid-cols-1 gap-4">
                    @foreach($addresses as $address)
                      <label class="relative cursor-pointer group">
                        <input type="radio" name="address_id" value="{{ $address->id }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                        <div class="p-4 rounded-lg border-2 border-gray-200 hover:border-brand-400 peer-checked:border-brand-600 peer-checked:bg-orange-50 transition-all">
                          <div class="flex items-start gap-3">
                             <div class="mt-1 text-brand-600">
                                <i class="far fa-dot-circle peer-checked:fas peer-checked:fa-check-circle"></i>
                             </div>
                             <div>
                                <span class="font-bold text-gray-800 text-sm">{{ $address->label ?? 'Alamat' }}</span>
                                <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                    {{ $address->specific_address }}, {{ $address->city }}, {{ $address->province }}
                                </p>
                             </div>
                          </div>
                        </div>
                      </label>
                    @endforeach
                  </div>
                @endif
            </div>
          </div>

          <!-- Review Produk -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h2 class="font-bold text-gray-800 flex items-center">
                  <i class="fas fa-box text-brand-600 mr-2"></i> Rincian Barang
                </h2>
            </div>
            <div class="p-6 space-y-5">
                @foreach($cartItems as $item)
                <div class="flex gap-4">
                    <div class="w-16 h-16 bg-gray-200 rounded-md overflow-hidden shrink-0">
                        <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800 text-sm">{{ $item->product->name }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-800 text-sm">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
          </div>
        </div>

        <!-- KOLOM KANAN: RINGKASAN -->
        <div class="lg:col-span-4">
          <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-24">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Belanja</h3>

            @php
              $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
              $shipping = 10000;
              $total = $subtotal + $shipping;
            @endphp

            <div class="space-y-3 text-sm text-gray-600 pb-4 border-b">
              <div class="flex justify-between">
                <span>Total Harga ({{ $cartItems->count() }} Barang)</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
              </div>
              <div class="flex justify-between">
                <span>Ongkos Kirim</span>
                <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
              </div>
            </div>

            <div class="flex justify-between items-center py-4">
              <span class="text-base font-bold text-gray-800">Total Tagihan</span>
              <span class="text-xl font-bold text-brand-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="w-full bg-brand-600 text-white font-bold py-3.5 rounded-lg hover:bg-brand-700 transition-all flex justify-center items-center group">
              <span>Buat Pesanan</span>
              <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </button>
          </div>
        </div>

      </div>
    </div>
  </form>
</body>
</html>
