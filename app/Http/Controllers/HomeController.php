<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Image::where('is_active', true)->with(['user', 'likes.user', 'comments.user']);
        if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($query) use ($search) {
        $query->where('title', 'like', "%{$search}%")
        ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            });
            
        }

        $images = $query->latest()->get();
        return view('home.index', compact('images'));
    }
    public function indexadmin(Request $request)
    {
        $query = Image::where('is_active', true)->with(['user', 'likes.user', 'comments.user']);
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            });
            
        }

        $images = $query->latest()->get();
        return view('home.index-admin', compact('images'));
 
   }
    public function create()
    {
        return view('home.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename); 
    
        Image::create([
            'title' => $request->title,
            'category' => $request->category,
            'image_path' => 'uploads/' . $filename, 
            'user_id' => auth()->id(),
            'is_active' => false,
        ]);
    
        return redirect()->route('home.index')->with('success', 'Gambar berhasil diupload, tunggu persetujuan admin.');
    }

    public function destroy($id)
{
    $image = Image::findOrFail($id);

   
    if ($image->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Kamu tidak punya izin untuk menghapus foto ini.');
    }

    
    if (file_exists(public_path($image->image_path))) {
        unlink(public_path($image->image_path));
    }

    $image->delete();

    return redirect()->route('home.index')->with('success', 'Foto berhasil dihapus.');
}

}