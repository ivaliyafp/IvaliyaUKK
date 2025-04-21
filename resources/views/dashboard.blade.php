@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Profil Header -->
        <div class="flex flex-col md:flex-row items-center justify-between bg-white p-6 rounded-lg shadow mb-6">
            <div class="flex items-center space-x-4">
              
                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="avatar" class="w-full h-full object-cover">
                </div>
                <!-- Info -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-600 text-sm">📤 Total Gambar: {{ $images->count() }}</p>
                    <p class="text-gray-500 text-sm mt-1">🎨 Gambar kamu akan tampil setelah disetujui admin.</p>
                </div>
            </div>
            <div class="mt-4 md:mt-0 space-x-2">
                <a href="{{ route('home.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    ➕ Upload
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Galeri -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">📸 Galeri Kamu</h3>

            @if ($images->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach ($images as $image)
                        <div class="bg-white border rounded-lg overflow-hidden shadow hover:shadow-md transition">
                            <img src="{{ asset($image->image_path) }}" alt="{{ $image->title }}" class="w-full h-48 object-cover">
                            <div class="p-3">
                                <h4 class="text-md font-semibold text-gray-700 truncate">{{ $image->title }}</h4>
                                <p class="text-xs mt-1 text-gray-500">
                                    Status: 
                                    @if ($image->is_active)
                                        <span class="text-green-600 font-semibold">Disetujui</span>
                                    @else
                                        <span class="text-yellow-600 font-semibold">Menunggu</span>
                                    @endif
                                </p>

                                <!-- Like info -->
                                @if ($image->likes->count())
                                    <p class="text-xs mt-1 text-gray-500">
                                        ❤️ Disukai oleh: 
                                        @foreach ($image->likes as $like)
                                            <span class="text-blue-600">{{ $like->user->name }}</span>@if (!$loop->last), @endif
                                        @endforeach
                                    </p>
                                @endif

                                <!-- Delete -->
                                <form action="{{ route('home.destroy', $image->id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin mau hapus gambar ini?')"
                                            class="w-full bg-red-500 text-white py-1 rounded hover:bg-red-600 text-sm">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-600 text-sm text-center">Kamu belum mengupload gambar apapun 😢</p>
            @endif
        </div>

    </div>
</div>
@endsection
