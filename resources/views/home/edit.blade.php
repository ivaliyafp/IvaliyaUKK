@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Edit Gambar</h4>
    <form action="{{ route('admin.images.update', $image->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="form-label">Judul</label>
            <input type="text" name="title" value="{{ $image->title }}" class="form-control" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Gambar Lama</label><br>
            <img src="{{ asset('storage/' . $image->image_path) }}" width="120" alt="">
        </div>
        <div class="mb-4">
            <label for="image" class="form-label">Ganti Gambar</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="mb-4">
            <label for="user_id" class="form-label">User</label>
            <select name="user_id" class="form-select" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" {{ $image->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ $image->description }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.images.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
