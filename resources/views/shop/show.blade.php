@extends('layouts.app')

@section('title', $shop->shop_name . ' - Nordic Store')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h1 class="text-4xl font-bold mb-2">{{ $shop->shop_name }}</h1>
        <p class="text-gray-600">@{{ $shop->instagram_username }} • {{ $shop->products_count ?? 0 }} products</p>
    </div>
    @if ($cart && $cart->items()->count() > 0)
        <a href="{{ route('cart.show', $shop) }}" class="w-full md:w-auto text-center px-6 py-3 bg-gray-900 text-white rounded hover:bg-gray-800 font-bold">
            🛒 Cart ({{ $cart->items_count ?? 0 }})
        </a>
    @endif
</div>

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse ($products as $product)
        <div class="bg-white border border-gray-200 rounded overflow-hidden hover:shadow-lg transition">
            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->title }}" class="w-full aspect-square object-cover hover:grow">
            <div class="p-4">
                <h3 class="font-bold text-sm mb-2 line-clamp-2">{{ $product->title ?? 'Untitled' }}</h3>
                <p class="text-gray-900 font-bold mb-4">{{ $product->formatted_price }}</p>
                <form action="{{ route('cart.add', [$shop, $product]) }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="w-full px-4 py-2 bg-gray-900 text-white text-sm rounded hover:bg-gray-800">
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-600">No products available yet.</p>
        </div>
    @endforelse
</div>

{{ $products->links() }}
@endsection
