@extends('layouts.app')
@section('content')
<div class="container py-4">
    <h2 class="text-xl font-semibold mb-4">Upload Gambar</h2>
    @if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif
    <form action="{{ route('home.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="title" class="block font-medium">Judul Gambar</label>
        <input type="text" name="title" id="title" required
               class="w-full border rounded px-3 py-2">
    </div>
    <div class="mb-4">
    <label for="category">Kategori</label>
    <input type="text" name="category" placeholder="Kategori..." class="...">
</div>
    <div class="mb-4">
        <label for="image" class="block font-medium">Pilih Gambar</label>
        <input type="file" name="image" id="image" required
               class="w-full border rounded px-3 py-2">
    </div>
    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
        Upload
    </button>
</form>
</div>
@endsection
