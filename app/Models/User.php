<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[Fillable(['name', 'email', 'password', 'avatar'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar ? url('storage/'.$this->avatar) : null;
    }

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * @return HasMany<Notification, $this>
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id', 'id')
            ->where('is_read', false);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return Collection<int, string>
     */
    public static function getCleanedUserId(array $data): Collection
    {
        return getByPatternId($data, '/@[\d]+/', '/@/')
            ->filter(function ($id) {
                return User::where('id', $id)->exists();
            });
    }

    /**
     * @return BelongsToMany<Message, $this>
     */
    public function likedMessages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'message_user_likes', 'user_id', 'message_id');
    }

    /**
     * @return BelongsToMany<Message, $this>
     */
    public function complaintedMessages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'complaints', 'user_id', 'message_id');
    }

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
}
