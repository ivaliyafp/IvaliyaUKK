<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['user_id', 'image_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }
    public function likes()
{
    return $this->hasMany(CommentLike::class);
}

public function isLikedBy($user)
{
    return $this->likes->where('user_id', $user->id)->count() > 0;
}

}
