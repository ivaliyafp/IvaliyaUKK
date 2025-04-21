@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <form action="{{ route('admin.images.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
        <label for="title" class="block font-medium">Judul Gambar</label>
        <input type="text" name="title" id="title" required
               class="w-full border rounded px-3 py-2">
    </div>
        <div class="mb-4">
            <label for="image" class="form-label">Upload Gambar</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <div class="mb-4">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" class="form-select" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
            <div class="mb-4">
        <label for="category">Kategori</label>
        <input type="text" name="category" placeholder="Kategori..." class="...">
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
        <a href="{{ route('admin.images.index') }}" class="bg-blue-500 hover:bg-red-600 text-white px-4 py-2 rounded">Batal</a>
    </form>
</div>
@endsection
