<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MessageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property bool|null $is_not_solved_complaint
 * @property bool|null $is_liked
 * @property-read Collection<int, Message> $messages
 */
class Message extends Model
{
    /** @use HasFactory<MessageFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $withCount = ['likedUsers'];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo<Theme, $this>
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'theme_id', 'id');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function likedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_user_likes', 'message_id', 'user_id');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function answeredUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_user_answers', 'message_id', 'user_id');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function complaintedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'complaints', 'message_id', 'user_id');
    }
}
