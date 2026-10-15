<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar ? url('storage/' . $this->avatar) : null;
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'user_id', 'id')
            ->where('is_read', false);
    }

    public static function getCleanedUserId ($data)
    {
       return getByPatternId($data, '/@[\d]+/', '/@/')
            ->filter(function ($id) {
                return User::where('id', $id)->exists();
            });
    }

    public function likedMessages()
    {
        return $this->belongsToMany(Message::class, 'message_user_likes', 'user_id', 'message_id');
    }
    public function complaintedMessages()
    {
        return $this->belongsToMany(Message::class, 'complaints', 'user_id', 'message_id');
    }
}
