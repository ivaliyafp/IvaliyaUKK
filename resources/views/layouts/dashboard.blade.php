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
                    <span class="text-sm text-blue-600">Hai, {{ Auth::user()->name }} ✨</span>
                    <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-blue-600">Dashboard</a>
                    <a href="{{ route('home.indexadmin') }}" class="text-sm hover:text-blue-600">Galeri</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500 hover:underline text-sm">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm text-blue-600 hover:underline">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    
    <div class="flex min-h-screen">
        <aside class="w-64 bg-white border-r flex flex-col">
            <div class="p-6 font-bold text-xl text-indigo-600">LiysGallery</div>
            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-200">📊 Dashboard</a>
                <a href="{{ route('admin.images.index') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-200">📁 Projects</a>
                <a href="{{ route('admin.pending.images') }}"class="flex items-center px-4 py-2 rounded hover:bg-gray-200">📋 Leads</a>
                <a href="{{ route('admin.images.create') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-200">+ upload</a>
                <a href="{{ route('kontak') }}" class="flex items-center px-4 py-2 rounded hover:bg-gray-200"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A4 4 0 016 16h12a4 4 0 01.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
</svg>
 kontak</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2 mt-4 text-left rounded hover:bg-red-100 text-red-600">
                        🚪 Logout
                    </button>
                </form>
            </nav>
        </aside>

       
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
