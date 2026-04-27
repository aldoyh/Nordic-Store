@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">Create Account</h1>
        <p class="text-gray-600">Join the Nordic marketplace</p>
    </div>

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-bold mb-2">Name</label>
            <input
                type="text"
                name="name"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('name') border-red-500 @enderror"
                value="{{ old('name') }}"
            >
            @error('name')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-2">Email</label>
            <input
                type="email"
                name="email"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('email') border-red-500 @enderror"
                value="{{ old('email') }}"
            >
            @error('email')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-2">Password</label>
            <input
                type="password"
                name="password"
                class="w-full px-4 py-2 border border-gray-300 rounded @error('password') border-red-500 @enderror"
            >
            @error('password')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
        </div>

        <div>
            <label class="block text-sm font-bold mb-2">Confirm Password</label>
            <input
                type="password"
                name="password_confirmation"
                class="w-full px-4 py-2 border border-gray-300 rounded"
            >
        </div>

        <button type="submit" class="w-full px-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800 font-bold">
            Register
        </button>

        <p class="text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('login') }}" class="text-gray-900 hover:underline font-bold">Login</a>
        </p>
    </form>
</div>
@endsection
