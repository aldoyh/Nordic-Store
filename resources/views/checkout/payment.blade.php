@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <h1 class="text-3xl font-bold mb-8">Payment</h1>

        <div class="bg-blue-50 border border-blue-200 rounded p-6 mb-8">
            <p class="text-sm text-blue-700">
                <strong>Demo Mode:</strong> This is a demo payment form. No actual charges will be made.
            </p>
        </div>

        <form action="{{ route('checkout.payment.store', $shop) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold mb-2">Card Number</label>
                <input type="text" placeholder="4242 4242 4242 4242" class="w-full px-4 py-2 border border-gray-300 rounded" value="4242 4242 4242 4242">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2">Expiry</label>
                    <input type="text" placeholder="12/25" class="w-full px-4 py-2 border border-gray-300 rounded" value="12/25">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">CVC</label>
                    <input type="text" placeholder="123" class="w-full px-4 py-2 border border-gray-300 rounded" value="123">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2">Cardholder Name</label>
                <input type="text" placeholder="John Doe" class="w-full px-4 py-2 border border-gray-300 rounded">
            </div>

            <button type="submit" class="w-full px-6 py-3 bg-gray-900 text-white rounded hover:bg-gray-800 font-bold">
                Complete Payment
            </button>
        </form>
    </div>

    <div class="lg:col-span-1">
        <div class="border border-gray-200 rounded p-6 sticky top-6">
            <h3 class="font-bold text-lg mb-4">Order Summary</h3>
            <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                @foreach ($order->items as $item)
                    <div class="flex justify-between text-sm">
                        <span>{{ $item->product_title }} x{{ $item->quantity }}</span>
                        <span>£{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="space-y-2 mb-6 pb-6 border-b border-gray-200">
                <div class="flex justify-between text-sm">
                    <span>Subtotal</span>
                    <span>£{{ number_format($order->total_price, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Shipping</span>
                    <span>£0.00</span>
                </div>
            </div>
            <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>£{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
