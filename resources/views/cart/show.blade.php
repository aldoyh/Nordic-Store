@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-2">Shopping Cart</h1>
    <p class="text-gray-600">{{ $shop->shop_name }}</p>
</div>

@if ($items->isEmpty())
    <div class="text-center py-12">
        <p class="text-gray-600 mb-4">Your cart is empty</p>
        <a href="{{ route('shop.show', $shop) }}" class="inline-block px-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800">
            Continue Shopping
        </a>
    </div>
@else
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="space-y-4">
                @foreach ($items as $item)
                    <div class="flex gap-4 p-4 border border-gray-200 rounded">
                        <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->title }}" class="w-24 h-24 object-cover rounded">
                        <div class="flex-1">
                            <h3 class="font-bold mb-1">{{ $item->product->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $item->product->formatted_price }} each</p>
                            <form action="{{ route('cart.update', [$shop, $item]) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="100" class="w-16 px-2 py-1 border border-gray-300 rounded">
                                <button type="submit" class="text-sm px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Update</button>
                            </form>
                        </div>
                        <div class="text-right">
                            <p class="font-bold mb-3">£{{ number_format($item->subtotal, 2) }}</p>
                            <form action="{{ route('cart.remove', [$shop, $item]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Remove</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="border border-gray-200 rounded p-6 sticky top-6">
                <h3 class="font-bold text-lg mb-4">Order Summary</h3>
                <div class="space-y-2 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span>£{{ number_format($cart->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Shipping</span>
                        <span>£0.00</span>
                    </div>
                </div>
                <div class="flex justify-between font-bold text-lg mb-6">
                    <span>Total</span>
                    <span>£{{ number_format($cart->total_price, 2) }}</span>
                </div>
                <a href="{{ route('checkout.shipping', $shop) }}" class="block w-full text-center px-6 py-3 bg-gray-900 text-white rounded hover:bg-gray-800 mb-3">
                    Proceed to Checkout
                </a>
                <a href="{{ route('shop.show', $shop) }}" class="block w-full text-center px-6 py-3 border border-gray-300 rounded hover:bg-gray-50">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
@endif
@endsection
