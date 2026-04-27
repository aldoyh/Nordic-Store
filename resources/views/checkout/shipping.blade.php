@extends('layouts.app')

@section('title', 'Shipping Information')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <h1 class="text-3xl font-bold mb-8">Shipping Information</h1>

        <form action="{{ route('checkout.shipping.store', $shop) }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold mb-2">Full Name</label>
                <input type="text" name="customer_name" class="w-full px-4 py-2 border border-gray-300 rounded @error('customer_name') border-red-500 @enderror" value="{{ old('customer_name') }}">
                @error('customer_name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold mb-2">Email</label>
                <input type="email" name="customer_email" class="w-full px-4 py-2 border border-gray-300 rounded @error('customer_email') border-red-500 @enderror" value="{{ old('customer_email', auth()->user()->email ?? '') }}">
                @error('customer_email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <label class="block text-sm font-bold mb-2">Address</label>
                <input type="text" name="address_line1" placeholder="Street address" class="w-full px-4 py-2 border border-gray-300 rounded @error('address_line1') border-red-500 @enderror" value="{{ old('address_line1') }}">
                @error('address_line1')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>

            <div>
                <input type="text" name="address_line2" placeholder="Apt, suite, etc." class="w-full px-4 py-2 border border-gray-300 rounded" value="{{ old('address_line2') }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-2">City</label>
                    <input type="text" name="city" class="w-full px-4 py-2 border border-gray-300 rounded @error('city') border-red-500 @enderror" value="{{ old('city') }}">
                    @error('city')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2">Postcode</label>
                    <input type="text" name="postcode" class="w-full px-4 py-2 border border-gray-300 rounded @error('postcode') border-red-500 @enderror" value="{{ old('postcode') }}">
                    @error('postcode')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-2">Country</label>
                <input type="text" name="country" class="w-full px-4 py-2 border border-gray-300 rounded @error('country') border-red-500 @enderror" value="{{ old('country') }}">
                @error('country')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="w-full px-6 py-3 bg-gray-900 text-white rounded hover:bg-gray-800 font-bold">
                Continue to Payment
            </button>
        </form>
    </div>

    <div class="lg:col-span-1">
        <div class="border border-gray-200 rounded p-6 sticky top-6">
            <h3 class="font-bold text-lg mb-4">Order Summary</h3>
            <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                @foreach ($items as $item)
                    <div class="flex justify-between text-sm">
                        <span>{{ $item->product->title }} x{{ $item->quantity }}</span>
                        <span>£{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>£{{ number_format($cart->total_price, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
