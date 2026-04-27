@extends('layouts.app')

@section('title', 'Create Shop from Instagram')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-2">Create Your Shop</h1>
        <p class="text-gray-600">Connect your Instagram account and start selling</p>
    </div>

    <div class="bg-gray-50 rounded-lg p-8">
        <form action="{{ route('instagram.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-bold mb-2">Instagram Username</label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-500">@</span>
                    <input
                        type="text"
                        name="instagram_username"
                        class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-gray-900 @error('instagram_username') border-red-500 @enderror"
                        placeholder="your_username"
                        value="{{ old('instagram_username') }}"
                    >
                </div>
                @error('instagram_username')
                    <span class="text-red-600 text-sm mt-2 block">{{ $message }}</span>
                @enderror
                <p class="text-gray-600 text-sm mt-3">
                    We'll fetch all public images from this Instagram profile and turn them into products you can price and sell.
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded p-4">
                <p class="text-sm text-blue-700">
                    <strong>Note:</strong> This will create a new shop. You can create multiple shops with different Instagram accounts.
                </p>
            </div>

            <button type="submit" class="w-full px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 font-bold">
                Create Shop
            </button>

            <a href="{{ route('dashboard') }}" class="block w-full text-center px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50">
                Back to Dashboard
            </a>
        </form>
    </div>
</div>
@endsection
