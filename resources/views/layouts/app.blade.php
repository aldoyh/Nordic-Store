<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Nordic Store')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@200;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Work Sans', sans-serif; }
        .hover\:grow { transition: transform 0.3s ease; }
        .hover\:grow:hover { transform: scale(1.02); }
    </style>
</head>
<body class="bg-white text-gray-900">
    <nav class="border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="{{ route('marketplace.index') }}" class="text-2xl font-bold">🌀 NORDICS</a>
            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-black">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-black">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-black">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-black">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <main class="max-w-6xl mx-auto px-6 py-12">
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 mt-16 py-12 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h3 class="font-bold mb-4">About</h3>
                    <p class="text-gray-600">Nordic Store - Instagram to E-Commerce Platform</p>
                </div>
                <div>
                    <h3 class="font-bold mb-4">Follow</h3>
                    <div class="flex gap-4">
                        <a href="#" class="text-gray-600 hover:text-black">Instagram</a>
                        <a href="#" class="text-gray-600 hover:text-black">Twitter</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
