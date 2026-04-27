@extends('layouts.app')

@section('title', 'Nordic Store - Instagram E-Commerce Marketplace')

@section('content')
<div class="mb-12">
    <h1 class="text-4xl font-bold mb-2">Welcome to Nordic Store</h1>
    <p class="text-gray-600">Discover amazing products from Instagram-connected vendors</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
    @forelse ($shops as $shop)
        <a href="{{ route('shop.show', $shop) }}" class="group">
            <div class="bg-gray-100 aspect-video mb-4 flex items-center justify-center hover:bg-gray-200 transition">
                <div class="text-center">
                    <div class="text-4xl mb-2">📸</div>
                    <p class="font-bold">{{ $shop->instagram_username }}</p>
                    <p class="text-sm text-gray-600">{{ $shop->products_count ?? 0 }} products</p>
                </div>
            </div>
            <h3 class="font-bold text-lg mb-1 group-hover:underline">{{ $shop->shop_name }}</h3>
            <p class="text-gray-600 text-sm">@{{ $shop->instagram_username }}</p>
        </a>
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-600 mb-4">No shops yet. Be the first vendor!</p>
            @auth
                <a href="{{ route('instagram.create') }}" class="inline-block px-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800">
                    Create Your Shop
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-block px-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800">
                    Register to Create a Shop
                </a>
            @endauth
        </div>
    @endforelse
</div>

{{ $shops->links() }}
@endsection
