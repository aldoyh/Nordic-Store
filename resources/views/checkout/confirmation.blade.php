@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="text-center mb-12">
    <div class="text-6xl mb-4">✓</div>
    <h1 class="text-4xl font-bold mb-2">Order Confirmed!</h1>
    <p class="text-xl text-gray-600 mb-4">Thank you for your purchase</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-gray-50 rounded p-8 mb-8">
            <h2 class="font-bold text-lg mb-4">Order Details</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Order Number</span>
                    <span class="font-bold">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Order Date</span>
                    <span>{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status</span>
                    <span class="font-bold text-green-600">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
        </div>

        <h2 class="font-bold text-lg mb-4">Items</h2>
        <div class="space-y-4 mb-8">
            @foreach ($items as $item)
                <div class="flex gap-4 p-4 border border-gray-200 rounded">
                    <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product_title }}" class="w-20 h-20 object-cover rounded">
                    <div class="flex-1">
                        <h3 class="font-bold">{{ $item->product_title }}</h3>
                        <p class="text-gray-600 text-sm">Qty: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">£{{ number_format($item->subtotal, 2) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="font-bold text-lg mb-4">Shipping Address</h2>
        <div class="bg-gray-50 rounded p-6 mb-8">
            <p class="whitespace-pre-line text-gray-700">{{ $order->delivery_address }}</p>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="border border-gray-200 rounded p-6 sticky top-6">
            <h3 class="font-bold text-lg mb-4">Order Summary</h3>
            <div class="space-y-2 mb-6 pb-6 border-b border-gray-200">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span>£{{ number_format($order->total_price, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Shipping</span>
                    <span>£0.00</span>
                </div>
            </div>
            <div class="flex justify-between font-bold text-lg mb-6">
                <span>Total</span>
                <span>£{{ number_format($order->total_price, 2) }}</span>
            </div>

            <div class="space-y-3">
                <a href="{{ route('marketplace.index') }}" class="block w-full text-center px-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800">
                    Continue Shopping
                </a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block w-full text-center px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">
                        View Orders
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
