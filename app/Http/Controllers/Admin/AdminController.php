<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('admin/dashboard');
    }

    public function pendingImages(){
        $images = Image::where('is_active',false)->latest()->get();
        return view('admin.pending-images', compact('images'));
    }

    public function approve($id)
    {
        $image =Image::findOrFail($id);
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
        if ($image->is_active) {
            return back()->with('info', 'Gambar sudah disetujui sebelumnya.');
        }
        $image->is_active = true;
    $image->save();

    return back()->with('success', 'Gambar berhasil disetujui.');

    }
}
