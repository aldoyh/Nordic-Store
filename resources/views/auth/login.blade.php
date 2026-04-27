@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2">Welcome Back</h1>
        <p class="text-gray-600">Login to manage your shops</p>
    </div>

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf

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

        <div class="flex items-center">
            <input type="checkbox" name="remember" class="rounded border-gray-300">
            <label class="text-sm text-gray-600 ml-2">Remember me</label>
        </div>

        <button type="submit" class="w-full px-6 py-2 bg-gray-900 text-white rounded hover:bg-gray-800 font-bold">
            Login
        </button>

        <p class="text-center text-sm text-gray-600">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-gray-900 hover:underline font-bold">Register</a>
        </p>
    </form>
</div>
@endsection
