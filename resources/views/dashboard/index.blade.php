@extends('layouts.app')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-4xl font-bold mb-2">Dashboard</h1>
        <p class="text-gray-600">Manage your shops and orders</p>
    </div>
    @auth
        <a href="{{ route('instagram.create') }}" class="px-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800">
            + Create Shop
        </a>
    @endauth
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <div class="bg-gray-50 rounded p-6">
        <p class="text-gray-600 mb-2">Total Shops</p>
        <p class="text-4xl font-bold">{{ $shops->count() }}</p>
    </div>
    <div class="bg-gray-50 rounded p-6">
        <p class="text-gray-600 mb-2">Total Orders</p>
        <p class="text-4xl font-bold">{{ $totalOrders }}</p>
    </div>
    <div class="bg-gray-50 rounded p-6">
        <p class="text-gray-600 mb-2">Total Revenue</p>
        <p class="text-4xl font-bold">£{{ number_format($totalRevenue, 2) }}</p>
    </div>
</div>

<h2 class="text-2xl font-bold mb-6">Your Shops</h2>

@if ($shops->isEmpty())
    <div class="text-center py-12 bg-gray-50 rounded">
        <p class="text-gray-600 mb-4">You haven't created any shops yet.</p>
        <a href="{{ route('instagram.create') }}" class="inline-block px-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800">
            Create Your First Shop
        </a>
    </div>
@else
    <div class="space-y-6 mb-12">
        @foreach ($shops as $shop)
            <div class="border border-gray-200 rounded p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xl font-bold">{{ $shop->shop_name }}</h3>
                        <p class="text-gray-600 text-sm">@{{ $shop->instagram_username }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">{{ $shop->products_count ?? 0 }} products</p>
                        <p class="text-sm font-bold">{{ $shop->orders_count ?? 0 }} orders</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('shop.show', $shop) }}" class="px-4 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50">
                        View Shop
                    </a>
                    <a href="#" class="px-4 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50">
                        Edit Products
                    </a>
                    <a href="#" class="px-4 py-2 border border-gray-300 rounded text-sm hover:bg-gray-50">
                        Settings
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

<h2 class="text-2xl font-bold mb-6">Recent Orders</h2>

@if (auth()->user()->orders()->count() === 0)
    <div class="text-center py-12 bg-gray-50 rounded">
        <p class="text-gray-600">No orders yet</p>
    </div>
@else
    <div class="border border-gray-200 rounded overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-bold">Order #</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Shop</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Date</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Total</th>
                    <th class="px-6 py-3 text-left text-sm font-bold">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach (auth()->user()->orders()->latest()->take(10)->get() as $order)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $order->order_number }}</td>
                        <td class="px-6 py-3 text-sm">{{ $order->shop->shop_name }}</td>
                        <td class="px-6 py-3 text-sm">{{ $order->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-sm font-bold">£{{ number_format($order->total_price, 2) }}</td>
                        <td class="px-6 py-3 text-sm">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $order->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
@endsection
