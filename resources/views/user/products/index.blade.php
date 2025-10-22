@extends('user.layouts.app')

@section('content')
<h2 class="text-2xl font-semibold mb-4">Daftar Produk</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    @foreach($products as $product)
    <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition">
        <h3 class="font-bold text-lg">{{ $product->name }}</h3>
        <p class="text-gray-500 text-sm">{{ $product->category->name }}</p>
        <p class="text-blue-600 font-semibold mt-2">Rp {{ number_format($product->price,0,',','.') }}</p>
        <form action="{{ route('user.cart.store', $product->id) }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 w-full">
                Tambah ke Keranjang
            </button>
        </form>
    </div>
    @endforeach
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
