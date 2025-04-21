@extends('layouts.dashboard')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">📋 Daftar Pengguna</h1>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800">
                    <th class="p-4 text-sm font-semibold tracking-wide">Nama</th>
                    <th class="p-4 text-sm font-semibold tracking-wide">Email</th>
                    <th class="p-4 text-sm font-semibold tracking-wide">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $user)
                <tr class="hover:bg-blue-50 transition duration-150">
                    <td class="p-4 text-gray-800">{{ $user->name }}</td>
                    <td class="p-4 text-gray-700">{{ $user->email }}</td>
                    <td class="p-4 text-gray-600">{{ $user->created_at->diffForHumans() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
