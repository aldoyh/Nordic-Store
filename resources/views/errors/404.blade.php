@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="text-center py-24">
    <h1 class="text-6xl font-bold mb-4 text-gray-900">404</h1>
    <p class="text-2xl mb-6 text-gray-600">Page Not Found</p>
    <p class="text-gray-600 mb-8 max-w-md mx-auto">
        Sorry, we couldn't find the page you're looking for. It might have been moved or deleted.
    </p>
    <div class="space-y-3">
        <a href="{{ route('marketplace.index') }}" class="inline-block px-6 py-3 bg-gray-900 text-white rounded hover:bg-gray-800">
            Back to Marketplace
        </a>
        <a href="/" class="inline-block px-6 py-3 border border-gray-300 rounded hover:bg-gray-50 ml-3">
            Go Home
        </a>
    </div>
</div>
@endsection
