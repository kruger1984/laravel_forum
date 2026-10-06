<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded  = false;

    protected $withCount = ['likedUsers'];

    public function getIsLikedAttribute()
    {
        return $this->likedUsers()->where('user_id', auth()->id())->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'message_user_likes', 'message_id', 'user_id');
    }
}
