<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $images = Image::with('user');
        if ($request->search) {
            $images = $images->where(function ($query) use ($request) {
                $query->where('id', $request->search)
                ->orwhere('title', 'like', '%' . $request->search . '%')
                    
                    ->orWhereHas('user', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        $images = $images->paginate(10);
        return view('admin.images.index',compact('images'));

}

    public function create(){
        $users = User::all();
        return view('admin.images.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'user_id' => 'required|exists:users,id',
        ]);

        $path = $request->file('image')->move(public_path('images'), time().'_'.$request->file('image')->getClientOriginalName());

        Image::create([
        'title' => $request->title,
        'image_path' => 'images/' . basename($path),
        'user_id' => $request->user_id,
        'description' => $request->description,
    ]);
        return redirect()->route('admin.images.index')->with('success', 'gambar telah di temukan');
    }
    public function edit(Image $image)
    {
        $users = User::all();
        return view('admin.images.edit', compact('image', 'users'));
    }
    public function update(Request $request, Image $image)
    {
    $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png',
        'user_id' => 'required|exists:users,id',
    ]);

    if ($request->hasFile('image')) {
        
        $oldPath = public_path($image->image_path);
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }

        $newPath = $request->file('image')->move(public_path('images'), time().'_'.$request->file('image')->getClientOriginalName());
        $image->image_path = 'images/' . basename($newPath);
    }

    $image->update([
        'title' => $request->title,
        'user_id' => $request->user_id,
        'description' => $request->description,
    ]);

    return redirect()->route('admin.images.index')->with('success', 'Gambar berhasil diupdate');
    }

    public function destroy(Image $image){
        $path = public_path($image->image_path);
    if (file_exists($path)) {
        unlink($path);
    }

    $image->delete();
    return back()->with('success', 'Gambar berhasil dihapus');
    }
    public function toggleActive(Image $image)
    {
        $image->is_active = !$image->is_active;
        $image->save();

        return back()->with('success', 'Status gambar berhasil diperbarui.');
    }
    public function pendingImages()
    {
        $images = Image::where('is_active', false)->with('user')->get();
        return view('admin.pending-images', compact('images'));
    }
    
}