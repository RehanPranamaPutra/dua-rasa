<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Dua Rasa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#f8f8e7',
                            600: '#dc3545',
                            700: '#b52d39'
                        }
                    }
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
        <input type="hidden" name="shipping_service" id="shipping_service">
        <input type="hidden" name="shipping_cost" id="shipping_cost">

        <div class="container mx-auto px-4 py-8">

            <!-- Alert Error -->
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

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">{{ session('error') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                <!-- KOLOM KIRI: ALAMAT & ITEM -->
                <div class="lg:col-span-8 space-y-6">

                    <!-- Pilih Alamat -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-4 border-b bg-gray-50 flex items-center justify-between">
                            <h2 class="font-bold text-gray-800 flex items-center">
                                <i class="fas fa-map-marker-alt text-brand-600 mr-2"></i>
                                Alamat Pengiriman
                            </h2>
                            <a href="{{ route('customer.address.create') }}"
                                class="text-sm font-bold text-brand-600 hover:underline">
                                + Tambah Alamat
                            </a>
                        </div>

                        <div class="p-6">
                            @if ($addresses->isEmpty())
                                <div class="text-center py-6 text-gray-500">
                                    <p class="mb-3">Belum ada alamat tersimpan.</p>
                                    <a href="{{ route('customer.address.create') }}"
                                        class="inline-block bg-brand-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-brand-700 transition">
                                        Tambah Alamat Baru
                                    </a>
                                </div>
                            @else
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach ($addresses as $address)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                                class="peer sr-only" {{ $loop->first ? 'checked' : '' }}
                                                data-rajaongkir="{{ $address->rajaongkir_city_id }}">
                                            <div
                                                class="p-4 rounded-lg border-2 border-gray-200
                                                    hover:border-brand-400
                                                    peer-checked:border-brand-600
                                                    peer-checked:bg-orange-50
                                                    transition-all">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="flex items-start gap-3">
                                                        <div class="mt-1 text-brand-600">
                                                            <i class="fas fa-check-circle hidden peer-checked:block"></i>
                                                            <i class="far fa-circle block peer-checked:hidden"></i>
                                                        </div>
                                                        <div>
                                                            <span class="font-bold text-gray-800 text-sm">
                                                                {{ $address->customer_name }}
                                                            </span>
                                                            <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                                                {{ $address->specific_address }},
                                                                {{ $address->city }},
                                                                {{ $address->province }}
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-1">
                                                                {{ $address->no_telp }}
                                                            </p>
                                                        </div>
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
                            @foreach ($cartItems as $item)
                                <div class="flex gap-4">
                                    <div class="w-16 h-16 bg-gray-200 rounded-md overflow-hidden shrink-0">
                                        @if ($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-gray-400">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-800 text-sm">{{ $item->product->name }}</h3>
                                        <!-- FIX: Menggunakan amount -->
                                        <p class="text-xs text-gray-500 mt-1">{{ $item->amount }} x Rp
                                            {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <!-- FIX: Menggunakan amount -->
                                        <p class="font-medium text-gray-800 text-sm">Rp
                                            {{ number_format($item->product->price * $item->amount, 0, ',', '.') }}
                                        </p>
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

                        <!-- Pilih Kurir (Hanya JNE) -->
                        <div class="mb-4">
                            <label class="font-bold text-sm mb-1 block">Kurir:</label>
                            <input type="text" value="JNE" disabled
                                class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" id="courier" value="jne">
                        </div>

                        <!-- Opsi Layanan Pengiriman -->
                        <div id="shipping-options" class="mb-4">
                            <!-- Opsi akan dimuat di sini oleh JavaScript -->
                        </div>

                        @php
                            // FIX: Perhitungan Subtotal dan Berat menggunakan 'amount'
                            $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->amount);
                            $totalWeight = ceil($cartItems->sum(fn($item) => ($item->product->weight ?? 1) * $item->amount) * 1000);
                            if ($totalWeight < 1) { $totalWeight = 1000; }
                        @endphp

                        <input type="hidden" id="total-weight" value="{{ (int) $totalWeight }}">

                        <div class="space-y-3 text-sm text-gray-600 pb-4 border-b">
                            <div class="flex justify-between">
                                <!-- FIX: Menghitung total kuantitas barang -->
                                <span>Total Harga ({{ $cartItems->sum('amount') }} Barang)</span>
                                <span id="subtotal-amount" data-subtotal="{{ $subtotal }}">Rp
                                    {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Ongkos Kirim</span>
                                <span id="shipping-amount" class="font-bold text-gray-800">Rp 0</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center py-4">
                            <span class="text-base font-bold text-gray-800">Total Tagihan</span>
                            <span id="total-amount" class="text-xl font-bold text-brand-600">Rp
                                {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>

                        <button type="submit"
                            class="w-full bg-brand-600 text-white font-bold py-3.5 rounded-lg hover:bg-brand-700 transition-all flex justify-center items-center group">
                            <span>Buat Pesanan</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <script>
        $(document).ready(function() {
            function formatRupiah(number) {
                return 'Rp ' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function calculateTotal(shippingCost = 0) {
                var subtotal = parseInt($('#subtotal-amount').data('subtotal'));
                var total = subtotal + shippingCost;
                $('#total-amount').text(formatRupiah(total));
            }

            function updateShippingOptions() {
                var shippingOptionsContainer = $('#shipping-options');
                var courier = $('#courier').val();
                var weight = Math.ceil($('#total-weight').val());
                var selectedAddress = $('input[name="address_id"]:checked');
                var destination = selectedAddress.attr('data-rajaongkir');

                if (!destination || destination === "" || destination === "undefined") {
                    shippingOptionsContainer.html('<p class="text-xs text-red-500">Pilih alamat pengiriman yang valid.</p>');
                    return;
                }

                shippingOptionsContainer.html(
                    '<p class="text-sm text-brand-600 animate-pulse"><i class="fas fa-sync fa-spin mr-2"></i>Menghitung ongkos kirim...</p>'
                );

                $.ajax({
                    url: '{{ route('calculate') }}',
                    method: 'POST',
                    data: {
                        destination: destination,
                        weight: weight,
                        courier: courier,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        shippingOptionsContainer.empty();
                        if (res.length > 0) {
                            shippingOptionsContainer.append(
                                '<label class="font-bold text-sm mb-2 block text-gray-700">Pilih Layanan Pengiriman:</label>'
                            );

                            $.each(res, function(i, item) {
                                var serviceId = 'service-' + i;
                                var card = `
                                    <label for="${serviceId}" class="flex items-center p-3 mb-2 border rounded-lg cursor-pointer hover:border-brand-500 bg-white transition-all shadow-sm">
                                        <input type="radio" id="${serviceId}" name="shipping_option"
                                            value="${item.value}"
                                            data-service-name="${item.service}"
                                            class="mr-3 w-4 h-4 text-brand-600">
                                        <div class="flex justify-between w-full items-center">
                                            <div class="pr-2">
                                                <span class="font-bold text-gray-800 text-sm">${item.service}</span>
                                                <p class="text-[10px] text-gray-500 uppercase">${item.description}</p>
                                                <p class="text-[10px] text-brand-600">Estimasi: ${item.etd} Hari</p>
                                            </div>
                                            <span class="font-bold text-sm text-gray-800">${formatRupiah(item.value)}</span>
                                        </div>
                                    </label>
                                `;
                                shippingOptionsContainer.append(card);
                            });

                            $('input[name="shipping_option"]').first().prop('checked', true).trigger('change');
                        } else {
                            shippingOptionsContainer.html(
                                '<p class="text-sm text-red-500">Kurir tidak tersedia untuk rute ini.</p>'
                            );
                        }
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON ? xhr.responseJSON.message : "Terjadi kesalahan server";
                        shippingOptionsContainer.html('<p class="text-sm text-red-500">Error: ' + msg + '</p>');
                    }
                });
            }

            $(document).on('change', 'input[name="shipping_option"]', function() {
                var cost = parseInt($(this).val());
                var name = $(this).attr('data-service-name');

                $('#shipping-amount').text(formatRupiah(cost));
                $('#shipping_service').val(name);
                $('#shipping_cost').val(cost);
                calculateTotal(cost);
            });

            $('input[name="address_id"]').on('change', updateShippingOptions);

            if ($('input[name="address_id"]:checked').length > 0) {
                updateShippingOptions();
            }
        });
    </script>

</body>

</html>
