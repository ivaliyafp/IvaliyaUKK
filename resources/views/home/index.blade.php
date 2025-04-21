@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-2xl font-semibold mb-4">Galeri Foto</h2>
    <form method="GET" action="{{ route('home.index') }}" class="mb-6">
        <div class="flex flex-col sm:flex-row items-center gap-2">
            <input 
                type="text" 
                name="search" 
                placeholder="Cari berdasarkan nama pengguna atau kategori..." 
                class="w-full sm:w-1/2 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                value="{{ request('search') }}"
            >
            <button 
                type="submit" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow">
                Cari 🔍
            </button>
        </div>
    </form>
    @if(request('search'))
        <p class="mb-4 text-sm text-gray-600">
            Menampilkan hasil untuk: <span class="font-semibold">"{{ request('search') }}"</span>
        </p>
    @endif

    @auth
        <div class="mb-6">
            <a href="{{ route('home.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">
                Upload Gambar
            </a>
        </div>
    @endauth

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2">
    @forelse ($images as $image)
    <div class="max-w-[100px] bg-white rounded-lg shadow overflow-hidden">
    <div class="w-full aspect-square overflow-hidden cursor-pointer" ondblclick="likeImage({{ $image->id }})">

                <img src="{{ asset($image->image_path) }}" 
                     alt="Gambar" 
                     class="w-full max-h-60 object-cover hover:scale-105 transition-transform duration-300" class="h-full object-contain">
            </div>

            <div class="p-1 flex flex-col  flex-grow text-xs">

                    @if (!$image->is_active)
                        <p class="text-yellow-600 text-sm mb-2">Menunggu persetujuan admin</p>
                        @auth
                            @if (Auth::user()->is_admin)
                                <form action="{{ route('admin.home.approve', $image->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyetujui gambar ini?')">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded mb-2">
                                        Setujui
                                    </button>
                                </form>
                            @endif
                        @endauth
                    @endif

                    <p class="text-sm text-gray-600 mb-1">
                        Diunggah oleh: {{ $image->user->name ?? 'Tidak diketahui' }}
                    </p>

                    <div class="flex items-center justify-between mb-1">
                        @auth
                        <form id="like-form-{{ $image->id }}" action="{{ route('images.like', $image->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-red-500 hover:text-red-600">
                                    ❤️ {{ $image->likes->count() }}
                                </button>
                            </form>

                            @else
                            <a href="{{ route('login') }}" class="text-gray-400 text-sm hover:text-red-500 transition">
                                ❤️ {{ $image->likes->count() }}
                            </a>
                        @endauth


                        <a href="#" class="text-blue-500 text-sm hover:underline" onclick="toggleComments({{ $image->id }})">
                            💬 Komentar ({{ $image->comments->count() }})
                        </a>
                    </div>

    
<div id="comments-{{ $image->id }}" class="hidden mt-2">
    @foreach ($image->comments as $comment)
        <div class="text-sm text-gray-700 border-t pt-1 mt-1 relative group">
            <strong>{{ $comment->user->name ?? 'Anonim' }}:</strong> {{ $comment->content }}
            <br>
            <small class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</small>

            @auth
               
                <form action="{{ route('comment.like', $comment->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-pink-500 text-xs ml-2 hover:underline">
                        💖 {{ $comment->likes->count() }}
                    </button>
                </form>

               
                @if (Auth::id() === $comment->user_id || Auth::user()->is_admin)
                    <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="absolute top-0 right-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin ingin menghapus komentar ini?')" class="text-red-500 text-xs hover:underline hidden group-hover:inline">
                            ❌
                        </button>
                    </form>
                @endif
            @else
                <span class="text-gray-400 text-xs ml-2">💖 {{ $comment->likes->count() }}</span>
            @endauth
        </div>
    @endforeach

    @auth
        <form action="{{ route('comment.store', $image->id) }}" method="POST" class="mt-2">
            @csrf
            <input type="text" name="content" placeholder="Tulis komentar..." class="w-full border rounded px-2 py-1 text-sm" required>
        </form>
    @endauth
</div>


                </div>
            </div>
        @empty
            <p class="text-gray-500">Tidak ada gambar yang ditampilkan.</p>
        @endforelse
    </div>
</div>

<script>
    function toggleComments(id) {
        const commentDiv = document.getElementById('comments-' + id);
        commentDiv.classList.toggle('hidden');
    }

    function likeImage(imageId) {
        const likeForm = document.getElementById('like-form-' + imageId);
        if (likeForm) {
            likeForm.submit();
        }
    }
</script>
@endsection
