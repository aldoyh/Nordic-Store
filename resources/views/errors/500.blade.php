@extends('layouts.app')

@section('title', 'Server Error')

@section('content')
<div class="text-center py-24">
    <h1 class="text-6xl font-bold mb-4 text-gray-900">500</h1>
    <p class="text-2xl mb-6 text-gray-600">Server Error</p>
    <p class="text-gray-600 mb-8 max-w-md mx-auto">
        Something went wrong on our end. Please try again later or contact support if the problem persists.
    </p>
    <div class="space-y-3">
        <a href="{{ route('marketplace.index') }}" class="inline-block px-6 py-3 bg-gray-900 text-white rounded hover:bg-gray-800">
            Back to Marketplace
        </a>
    </div>
</div>
@endsection
