@extends('layouts.dashboard')

@section('content')
<div class="container py-4">
    <h2 class="text-2xl font-semibold mb-4">Gambar Menunggu Persetujuan</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @elseif(session('info'))
        <div class="bg-yellow-100 text-yellow-700 p-2 rounded mb-4">
            {{ session('info') }}
        </div>
    @endif
    @if($images->count())
    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="min-w-full table-auto text-sm text-gray-700">
            <thead class="bg-blue-200 text-gray-700 font-semibold text-sm text-center">
                <tr>
                    <th class="p-3 w-36">Gambar</th>
                    <th class="p-3 w-48">Judul</th>
                    <th class="p-3 w-52">Tanggal Upload</th>
                    <th class="p-3 w-52">Pengunggah</th>
                    <th class="p-3 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y text-center">
                @foreach ($images as $image)
                    <tr>
                        <td class="p-3">
                            <img src="{{ asset($image->image_path) }}" alt="gambar" class="w-24 h-16 object-cover rounded mx-auto">
                        </td>
                        <td class="p-3 text-nowrap">{{ $image->title }}</td>
                        <td class="p-3 text-nowrap">{{ $image->created_at->format('d M Y H:i') }}</td>
                        <td class="p-3 text-nowrap">{{ $image->user->name ?? 'Tidak diketahui' }}</td>
                        <td class="p-3">
                            <form action="{{ route('admin.approve.image', $image->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
                                    Setujui
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="text-gray-500 text-center mt-6">Tidak ada gambar yang perlu disetujui.</p>
    @endif
</div>
@endsection
