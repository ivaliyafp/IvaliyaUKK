@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Galeri Foto</h2>
        @auth
        <a href="{{ route('home.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-full text-sm">
            Tambah Foto
        </a>
        @endauth
    </div>

    <form method="GET" action="{{ route('home.index') }}" class="mb-6">
        <div class="relative flex items-center">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}"
                placeholder="Cari nama pengguna / kategori..." 
                class="w-full px-4 py-3 border border-gray-200 rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-red-400"
            >
            <button type="submit" class="absolute right-3 text-gray-500">
                🔍
            </button>
        </div>
    </form>

    @if(request('search'))
        <div class="mb-4 text-gray-600 text-sm">
            Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong>
        </div>
    @endif

    <div class="masonry-grid">
        @forelse ($images as $image)
            <div class="masonry-item mb-4 relative group">
                <img 
                    src="{{ asset($image->image_path) }}" 
                    alt="Galeri foto" 
                    class="w-full rounded-lg object-cover hover:brightness-95 transition duration-300 cursor-pointer"
                    loading="lazy"
                    onclick="openImageDetail({{ $image->id }})"
                >
                
                <div class="invisible group-hover:visible absolute bottom-2 right-2 flex space-x-2">
                    @auth
                        <a href="{{ asset($image->image_path) }}" 
                           download 
                           class="bg-white rounded-full p-2 shadow-md hover:bg-gray-100">
                            ⬇️
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           onclick="return confirm('Login dulu ya buat unduh gambar 😊')" 
                           class="bg-white rounded-full p-2 shadow-md hover:bg-gray-100">
                            ⬇️
                        </a>
                    @endauth
                    
                    <button onclick="event.stopPropagation(); toggleComments({{ $image->id }})" 
                            class="bg-white rounded-full p-2 shadow-md hover:bg-gray-100">
                        💬
                    </button>
                    
                    @auth
                        <form id="like-form-{{ $image->id }}" action="{{ route('images.like', $image->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="event.stopPropagation();" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-100">
                                ❤️
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" onclick="event.stopPropagation();" class="bg-white rounded-full p-2 shadow-md hover:bg-gray-100">❤️</a>
                    @endauth
                </div>
                
                @if (!$image->is_active)
                    <div class="absolute top-2 left-2 text-yellow-600 bg-white px-2 py-1 rounded-full text-xs shadow">
                        ⏳ Menunggu Persetujuan
                    </div>
                    @auth
                        @if (Auth::user()->is_admin)
                            <form action="{{ route('admin.home.approve', $image->id) }}" method="POST" class="absolute top-2 right-2">
                                @csrf
                                <button onclick="event.stopPropagation();" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 text-xs rounded-full shadow">
                                    ✔️ Setujui
                                </button>
                            </form>
                        @endif
                    @endauth
                @endif
            </div>
        @empty
            <div class="text-gray-500 text-center py-8">Tidak ada gambar untuk ditampilkan.</div>
        @endforelse
    </div>
</div>

<!-- Image Detail Modal - Full Screen without dark overlay -->
@foreach ($images as $image)
<div id="image-detail-{{ $image->id }}" class="fixed inset-0 z-50 hidden bg-white overflow-auto">
    <div class="min-h-screen flex flex-col">
        <div class="w-full relative">
            <div class="flex justify-between items-center py-3 px-4 border-b">
                <button 
                    onclick="closeImageDetail({{ $image->id }})" 
                    class="flex items-center text-gray-600 hover:text-gray-900"
                >
                    ← Kembali
                </button>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <form action="{{ route('images.like', $image->id) }}" method="POST" class="inline">
                            @csrf
                            <button class="text-gray-700 hover:text-red-600 flex items-center space-x-1">
                                <span>❤️</span>
                                <span>{{ $image->likes->count() }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 flex items-center space-x-1">
                            <span>❤️</span>
                            <span>{{ $image->likes->count() }}</span>
                        </a>
                    @endauth
                    
                    @auth
                        <a href="{{ asset($image->image_path) }}" download class="text-gray-700 hover:text-blue-600 flex items-center space-x-1">
                            <span>⬇️</span>
                            <span>Unduh</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" onclick="return confirm('Login dulu ya buat unduh gambar 😊')" class="text-gray-700 flex items-center space-x-1">
                            <span>⬇️</span>
                            <span>Unduh</span>
                        </a>
                    @endauth
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row">
                <!-- Image Side -->
                <div class="md:w-2/3 flex items-center justify-center bg-gray-50">
                    <div class="relative w-full">
                        <img 
                            src="{{ asset($image->image_path) }}" 
                            alt="Detail foto" 
                            class="w-full h-auto max-h-[90vh] object-contain"
                        >
                    </div>
                </div>
                
                <!-- Info Side -->
                <div class="md:w-1/3 p-6 overflow-y-auto max-h-[90vh]">
                    <div class="mb-4">
                        <div class="text-lg font-bold">{{ $image->user->name ?? 'Anonim' }}</div>
                        <p class="text-gray-600 text-sm">{{ $image->created_at->format('d M Y') }}</p>
                    </div>
                    
                    @if($image->description)
                    <p class="mb-4 text-gray-800">
                        {{ $image->description }}
                    </p>
                    @endif
                    
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="font-bold mb-2">{{ $image->comments->count() }} Komentar</h3>
                        
                        <div class="space-y-4 mb-4 max-h-[300px] overflow-y-auto">
                            @forelse($image->comments as $comment)
                                <div class="flex items-start space-x-2">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        {{ substr($comment->user->name ?? 'A', 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center">
                                            <span class="font-bold text-sm">{{ $comment->user->name ?? 'Anonim' }}</span>
                                            <small class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="text-sm">{{ $comment->content }}</p>
                                        <div class="text-xs mt-1 flex items-center">
                                            @auth
                                                <form action="{{ route('comment.like', $comment->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button class="text-gray-500 hover:text-red-500">💖 {{ $comment->likes->count() }}</button>
                                                </form>
                                                
                                                @if (Auth::id() === $comment->user_id || Auth::user()->is_admin)
                                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="inline ml-2">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button onclick="return confirm('Hapus komentar?')" class="text-gray-500 hover:text-red-500">❌</button>
                                                    </form>
                                                @endif
                                            @else
                                                <span class="text-gray-400">💖 {{ $comment->likes->count() }}</span>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">Belum ada komentar.</p>
                            @endforelse
                        </div>
                        
                        @auth
                            <form action="{{ route('comment.store', $image->id) }}" method="POST">
                                @csrf
                                <div class="flex items-center bg-gray-100 rounded-full pl-4 pr-2 py-1">
                                    <input 
                                        type="text" 
                                        name="content" 
                                        placeholder="Tambahkan komentar..." 
                                        class="flex-1 bg-transparent text-sm focus:outline-none"
                                        required
                                    >
                                    <button type="submit" class="ml-2 bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center">
                                        ➤
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center p-2 border rounded">
                                <a href="{{ route('login') }}" class="text-red-600 hover:underline">Login untuk menambahkan komentar</a>
                            </div>
                        @endauth
                    </div>
                    
                    <h3 class="font-bold mb-2">Gambar Serupa</h3>
<div class="grid grid-cols-3 gap-2">
    @foreach($images->where('id', '!=', $image->id)->where('category', $image->category)->take(6) as $relatedImage)
        <div class="aspect-square overflow-hidden rounded-lg">
            <img 
                src="{{ asset($relatedImage->image_path) }}" 
                alt="Gambar serupa" 
                class="w-full h-full object-cover cursor-pointer hover:opacity-90"
                onclick="openImageDetail({{ $relatedImage->id }}); closeImageDetail({{ $image->id }})"
            >
        </div>
    @endforeach
</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Comments Modal -->
@foreach ($images as $image)
<div id="comments-{{ $image->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg max-w-lg w-full max-h-[80vh] overflow-y-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold">Komentar ({{ $image->comments->count() }})</h3>
            <button onclick="toggleComments({{ $image->id }})" class="text-gray-500 hover:text-gray-700">
                ✖️
            </button>
        </div>
        
        <div class="text-sm mb-2">
            <span class="font-bold">👤 {{ $image->user->name ?? 'Anonim' }}</span>
            <span class="ml-2">❤️ {{ $image->likes->count() }}</span>
        </div>
        
        <div class="border-t border-gray-200 my-2"></div>
        
        <div class="space-y-3 mb-4">
            @foreach ($image->comments as $comment)
                <div class="flex items-start space-x-2">
                    <div class="flex-shrink-0 w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                        {{ substr($comment->user->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <span class="font-bold text-sm">{{ $comment->user->name ?? 'Anonim' }}</span>
                            <small class="text-gray-400">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="text-sm">{{ $comment->content }}</p>
                        <div class="text-xs mt-1 flex items-center">
                            @auth
                                <form action="{{ route('comment.like', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-gray-500 hover:text-red-500">💖 {{ $comment->likes->count() }}</button>
                                </form>
                                
                                @if (Auth::id() === $comment->user_id || Auth::user()->is_admin)
                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Hapus komentar?')" class="text-gray-500 hover:text-red-500">❌</button>
                                    </form>
                                @endif
                            @else
                                <span class="text-gray-400">💖 {{ $comment->likes->count() }}</span>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        @auth
            <form action="{{ route('comment.store', $image->id) }}" method="POST">
                @csrf
                <div class="flex items-center">
                    <input 
                        type="text" 
                        name="content" 
                        placeholder="Tulis komentar..." 
                        class="flex-1 border rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-400"
                        required
                    >
                    <button type="submit" class="ml-2 bg-red-600 text-white rounded-full px-4 py-2 text-sm">
                        Kirim
                    </button>
                </div>
            </form>
        @else
            <div class="text-center p-2 border rounded">
                <a href="{{ route('login') }}" class="text-red-600 hover:underline">Login untuk menambahkan komentar</a>
            </div>
        @endauth
    </div>
</div>
@endforeach

<style>
    .masonry-grid {
        columns: 2 120px;
        column-gap: 16px;
    }
    
    @media (min-width: 640px) {
        .masonry-grid {
            columns: 3 180px;
        }
    }
    
    @media (min-width: 768px) {
        .masonry-grid {
            columns: 4 220px;
        }
    }
    
    @media (min-width: 1024px) {
        .masonry-grid {
            columns: 5 250px;
        }
    }
    
    .masonry-item {
        break-inside: avoid;
    }
    
    body.modal-open {
        overflow: hidden;
    }
</style>

<script>
    function toggleComments(id) {
        const commentDiv = document.getElementById('comments-' + id);
        commentDiv.classList.toggle('hidden');
        
        // Prevent scrolling when modal is open
        if (!commentDiv.classList.contains('hidden')) {
            document.body.style.overflow = 'hidden';
            document.body.classList.add('modal-open');
        } else {
            document.body.style.overflow = 'auto';
            document.body.classList.remove('modal-open');
        }
    }
    
    function openImageDetail(id) {
        const detailModal = document.getElementById('image-detail-' + id);
        detailModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        document.body.classList.add('modal-open');
    }
    
    function closeImageDetail(id) {
        const detailModal = document.getElementById('image-detail-' + id);
        detailModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.body.classList.remove('modal-open');
    }
    
    // Close modals when clicking escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const openModals = document.querySelectorAll('.fixed:not(.hidden)');
            openModals.forEach(modal => {
                if (modal.id.startsWith('image-detail-')) {
                    const id = modal.id.replace('image-detail-', '');
                    closeImageDetail(id);
                } else if (modal.id.startsWith('comments-')) {
                    const id = modal.id.replace('comments-', '');
                    toggleComments(id);
                }
            });
        }
    });
</script>
@endsection