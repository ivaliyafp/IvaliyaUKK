<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\comment;
use App\Models\Image;
use illuminate\support\facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Image $image)
    {
        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'image_id' => $image->id,
            'content' => $request->content,
        ]);

        return back();
    }
    public function destroy($id)
{
    $comment = Comment::findOrFail($id);
    
    if (auth()->user()->id !== $comment->user_id && !auth()->user()->is_admin) {
        abort(403, 'Tidak diizinkan.');
    }

    $comment->delete();

    return back()->with('success', 'Komentar berhasil dihapus.');
}

}
