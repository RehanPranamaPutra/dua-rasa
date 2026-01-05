@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-[#FDFDFD] py-12 px-4">
    <div class="container mx-auto max-w-5xl">

        <!-- Simple Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Riwayat Pesanan</h1>
                <p class="text-gray-500 mt-1">Kelola dan pantau semua pesanan kuliner Anda.</p>
            </div>

            <!-- Minimalist Filter Segment -->
            @php
                $currentStatus = request()->query('status', 'all');
                $filters = [
                    'all' => 'Semua',
                    'new' => 'Baru',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'delivered' => 'Selesai',
                ];
            @endphp
            <div class="flex bg-gray-100 p-1 rounded-xl w-fit overflow-x-auto no-scrollbar">
                @foreach ($filters as $status => $label)
                    <a href="{{ route('orders.filter', ['status' => $status]) }}"
                       class="px-5 py-2 rounded-lg text-sm font-bold transition-all duration-200 whitespace-nowrap
                       {{ $currentStatus == $status
                          ? 'bg-white text-duarasa-red shadow-sm'
                          : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Success Alert (Minimal) -->
        @if (session('success'))
            <div class="mb-6 animate-fade-in">
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Orders List -->
        <div class="space-y-4">
            @forelse ($orders as $order)
                <div class="group bg-white border border-gray-100 rounded-2xl p-5 md:p-6 transition-all duration-300 hover:border-red-100 hover:shadow-xl hover:shadow-red-500/5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">

                        <!-- Info Utama -->
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Invoice</span>
                                <span class="text-sm font-black text-gray-800">#{{ $order->invoice_number }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </h3>
                            <p class="text-xs text-gray-400 font-medium">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('d F Y • H:i') }} WIB
                            </p>
                        </div>

                        <!-- Status Tags -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Payment Status -->
                            @php
                                $payStyles = [
                                    'Pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'Berhasil' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'Gagal' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'Expired' => 'bg-gray-50 text-gray-500 border-gray-100',
                                ];
                                $payClass = $payStyles[$order->payment_status] ?? 'bg-gray-50 text-gray-600';
                            @endphp
                            <div class="px-3 py-1.5 rounded-lg border {{ $payClass }} text-[11px] font-black uppercase tracking-wider">
                                {{ $order->payment_status }}
                            </div>

                            <!-- Order Status -->
                            @php
                                $statusMap = [
                                    'new' => ['label' => 'Menunggu', 'color' => 'bg-blue-50 text-blue-600 border-blue-100'],
                                    'processing' => ['label' => 'Proses', 'color' => 'bg-indigo-50 text-indigo-600 border-indigo-100'],
                                    'shipped' => ['label' => 'Kurir', 'color' => 'bg-orange-50 text-orange-600 border-orange-100'],
                                    'delivered' => ['label' => 'Selesai', 'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100'],
                                    'cancelled' => ['label' => 'Batal', 'color' => 'bg-red-50 text-red-600 border-red-100'],
                                ];
                                $st = $statusMap[$order->order_status] ?? ['label' => $order->order_status, 'color' => 'bg-gray-50 text-gray-600 border-gray-100'];
                            @endphp
                            <div class="px-3 py-1.5 rounded-lg border {{ $st['color'] }} text-[11px] font-black uppercase tracking-wider">
                                {{ $st['label'] }}
                            </div>
                        </div>

                        <!-- Action -->
                        <div class="md:text-right">
                            <a href="{{ route('orders.show', $order->invoice_number) }}"
                               class="inline-flex items-center justify-center bg-gray-900 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-duarasa-red transition-all duration-300 group-hover:shadow-lg group-hover:shadow-red-500/20">
                                Detail Pesanan
                                <i class="fas fa-chevron-right ml-2 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State (Minimalist) -->
                <div class="py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-50 rounded-3xl mb-6">
                        <i class="fas fa-utensils text-gray-200 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Belum ada pesanan</h3>
                    <p class="text-gray-400 mt-2 mb-8">Sepertinya perut Anda butuh asupan dari DuaRasa Kitchen.</p>
                    <a href="{{ route('landing-page') }}#menu" class="bg-duarasa-red text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-500/30 hover:bg-red-700 transition-all">
                        Cari Menu Enak
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Minimal Pagination -->
        @if ($orders->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('styles')
<style>
    .text-duarasa-red { color: #dc3545; }
    .bg-duarasa-red { background-color: #dc3545; }

    /* Hide scrollbar for filter pills */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Custom Pagination Style Overrides (jika menggunakan Tailwind Pagination) */
    .pagination { @apply flex gap-2; }
    .page-item.active .page-link { @apply bg-gray-900 border-gray-900 text-white rounded-lg; }
    .page-link { @apply border-none bg-gray-100 text-gray-600 rounded-lg font-bold transition-all; }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.4s ease-out; }
</style>
@endsection
