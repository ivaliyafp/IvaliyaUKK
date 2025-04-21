<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'image_path', 'is_active'];
     
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
{
    return $this->hasMany(Like::class);
}
public function comments()
{
    return $this->hasMany(Comment::class);
}
public function isLikedBy($user)
{
    return $this->likes->where('user_id', $user->id)->count() > 0;
}
}
