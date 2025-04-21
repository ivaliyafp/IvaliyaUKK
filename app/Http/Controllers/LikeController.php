<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Image $image)
    {
        $user = Auth::user();

        $like = Like::where('user_id', $user->id)->where('image_id', $image->id)->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'image_id' => $image->id
            ]);
        }

        return back();
    }
}
