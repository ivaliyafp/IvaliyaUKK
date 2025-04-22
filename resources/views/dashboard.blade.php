@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        
        <div class="flex flex-col md:flex-row items-center justify-between bg-white p-6 rounded-lg shadow mb-6">
            <div class="flex items-center space-x-4">
              <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                @method('PUT')
                
                <input type="file" name="profile_image" accept="image/*" class="mb-2">
                <button type="+" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">Ganti Foto</button>
              </form>
                <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-300">
                    @if (Auth::user()->profile_image)
                    <img src="{{ asset(Auth::user()->profile_image) }}"  class="w-full h-full object-cover">
                    @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="avatar" class="w-full h-full object-cover">
                    @endif
                </div>
                
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
                        @if ($image->is_active == 1)
                            <span class="text-green-600 font-semibold">Disetujui</span>
                        @elseif ($image->is_active == 2)
                            <span class="text-red-600 font-semibold">Ditolak</span>
                        @else
                            <span class="text-yellow-600 font-semibold">Menunggu</span>
                        @endif
                    </p>
                    <p class="text-xs mt-1 text-gray-500">
                        ❤️ {{ $image->likes->count() }} Like • 
                        <a href="javascript:void(0);" class="text-blue-500 hover:underline"
                           onclick="toggleComments({{ $image->id }})">
                            💬 {{ $image->comments->count() }} Komentar
                        </a>
                    </p>
                    @if ($image->likes->count())
                                    <div class="text-xs text-gray-400 mt-1">
                                        Disukai oleh:
                                        @foreach ($image->likes as $like)
                                            {{ $like->user->name }}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </div>
                                @endif


                    <div id="comments-{{ $image->id }}" class="hidden mt-2">
                        @foreach ($image->comments as $comment)
                            <div class="text-sm text-gray-700 border-t pt-2 mt-2">
                                <strong>{{ $comment->user->name ?? 'Anonim' }}:</strong> {{ $comment->content }}
                                <div class="flex justify-between items-center text-xs text-gray-400 mt-1">
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    
                                    <form action="{{ route('comment.like', $comment->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-pink-500 hover:underline">
                                            💖 {{ $comment->likes->count() }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
        <script>
    function toggleComments(imageId) {
        const el = document.getElementById(`comments-${imageId}`);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
</script>

    </div>
</div>
@endsection
