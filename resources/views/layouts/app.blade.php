<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>liysgirl</title>
    <link rel="stylesheet" href="{{ asset('css/tailwind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

  
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                <i class="fas fa-camera mr-2"></i>
                liys Gallery
            </a>
            <div class="space-x-6 flex items-center">
                @auth
                    <span class="text-sm text-gray-600">Hai, {{ Auth::user()->name }} ✨</span>
                    @if (Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-700 hover:text-blue-600">Dashboard</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-blue-600">Dashboard</a>
                    @endif
                    <a href="{{ route('home.index') }}" class="text-sm text-gray-700 hover:text-blue-600">Galeri</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:underline">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    @hasSection('header')
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            @yield('header')
        </div>
    </header>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

</body>
</html>
