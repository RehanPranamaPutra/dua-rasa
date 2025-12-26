<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309'
                        }
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

    <nav class="bg-white border-b py-4 shadow-sm">
        <div class="container mx-auto px-4">
            <a href="/" class="text-2xl font-bold text-brand-700">Dua Rasa</a>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">

        <!-- Flash Success -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
                <p class="font-bold">Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Header Status -->
        <div class="bg-white rounded-xl shadow p-6 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-xl font-bold text-gray-800">Invoice: {{ $order->invoice_number }}</h1>
                <p class="text-gray-500 text-sm">Dipesan pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <!-- Badge Status -->
                <span
                    class="px-5 py-2 rounded-full text-sm font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase tracking-wide">
                    {{ $order->payment_status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Detail Produk -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-4 border-b bg-gray-50 font-bold text-gray-700">
                        <i class="fas fa-list mr-2 text-brand-600"></i> Rincian Produk
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach ($order->details as $detail)
                            <div class="flex gap-4">
                                <!-- Gambar Produk -->
                                <div class="w-16 h-16 bg-gray-100 rounded-lg overflow-hidden shrink-0 border">
                                    <img src="{{ asset('storage/' . $detail->product->image) }}"
                                        class="w-full h-full object-cover" alt="Produk">
                                </div>

                                <!-- Info Produk -->
                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-800 text-base">{{ $detail->product_name }}</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $detail->amount }} x Rp {{ number_format($detail->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Total Harga Item -->
                                <div class="text-right">
                                    <p class="font-bold text-gray-800">Rp
                                        {{ number_format($detail->total, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Info Pengiriman -->
                <div class="bg-white rounded-xl shadow overflow-hidden">
                    <div class="p-4 border-b bg-gray-50 font-bold text-gray-700">
                        <i class="fas fa-truck mr-2 text-brand-600"></i> Info Pengiriman
                    </div>
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="mt-1 text-gray-400"><i class="fas fa-map-marker-alt fa-lg"></i></div>
                            <div>
                                <p class="font-bold text-gray-800">{{ $order->address->label ?? 'Alamat' }}</p>
                                <p class="text-gray-600 mt-1">
                                    {{ $order->address->specific_address }},
                                    {{ $order->address->city }}, {{ $order->address->province }}
                                    {{ $order->address->postal_code }}
                                </p>
                                <p class="text-gray-500 text-sm mt-2">Penerima:
                                    {{ $order->address->recipient_name ?? $order->customer->name }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pembayaran -->

            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow p-6 sticky top-6 border border-gray-100">
                    <h3 class="font-bold text-lg mb-4 text-gray-800">Rincian Pembayaran</h3>

                    <div class="space-y-3 text-sm text-gray-600 pb-4 border-b">
                        <div class="flex justify-between">
                            <span>Total Harga Barang</span>
                            <!-- Kurangi total_price dengan shipping_cost -->
                            <span>Rp
                                {{ number_format($order->total_price - $order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pengiriman</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center py-4 mb-4">
                        <span class="text-lg font-bold text-gray-800">Total Bayar</span>
                        <span class="text-xl font-bold text-brand-600">Rp
                            {{ number_format($order->total_price + $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>

                    <!-- TOMBOL BAYAR (Placeholder) -->
                    @if ($order->payment_status != 'Berhasil' && isset($snapToken))
                        <button id="pay-button" class="...">
                            <i class="fas fa-lock mr-2"></i> Bayar Sekarang
                        </button>
                        <p class="text-xs text-center text-gray-400 mt-3">
                            Lakukan pembayaran untuk memproses pesanan Anda.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken acquired from previous step
            snap.pay('{{ $snapToken }}', {
                // Optional
                onSuccess: function(result) {
                    window.location.href = "{{ route('payment.success') }}"
                },
                // Optional
                onPending: function(result) {},
                // Optional
                onError: function(result) {}
            });
        };
    </script>

</body>

</html>
