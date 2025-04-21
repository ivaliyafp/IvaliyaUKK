@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen bg-gray-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">📷 Daftar Gambar</h2>
        </div>
        <form action="{{ route('admin.images.index') }}" method="GET" class="mb-4" style="max-width: 500px;">
    <div class="input-group">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-start" >
        <button class="btn btn-primary rounded-end" type="submit">
            Cari 🔍
        </button>
        <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>
        @if (session('success'))
            <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-800 shadow">
                {{ session('success') }}
            </div>
        @endif
        <div class="overflow-x-auto bg-white rounded-xl shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-200 text-gray-700 font-semibold text-sm">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Judul</th>
                        <th class="px-4 py-3 text-left">Gambar</th>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 pw-3 text-left">Tanggal Upload</th>

                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @foreach ($images as $index => $image)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $index + $images->firstItem() }}</td>
                            <td class="px-4 py-3 font-medium">{{ $image->title }}</td>
                            <td class="px-4 py-3">
                                <img src="{{ asset($image->image_path) }}" alt="gambar" class="w-24 h-24 object-cover rounded-md shadow">
                            </td>
                            <td class="px-4 py-3">{{ $image->user->name }}</td>
                            <td class="px-4 py-3">{{ $image->created_at->format('d M Y H:i') }}</td>
                           
                            <td class="px-4 py-3 flex space-x-2">
                                <a href="{{ route('admin.images.edit', $image->id) }}"
                                   class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded shadow text-xs">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.images.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow text-xs">
                                        🗑️ Hapus
                                    </button>
                                </form>
        
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $images->links() }}
        </div>
    </div>
</div>
@endsection