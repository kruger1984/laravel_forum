<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Log;

class Image extends Model
{
    protected $guarded = [];

    public function getUrlAttribute(): string|UrlGenerator
    {
        return url('storage/'.$this['path']);
    }

    /**
     * @param  Builder<self>  $builder
     */
    public function scopeCleanFromStorage(Builder $builder): void
    {
        $paths = $builder->where('user_id', auth()->id())
            ->whereNull('message_id')
            ->pluck('path');

        Log::info($paths);

        $paths->each(function ($path): void {
            Storage::disk('public')->delete($path);
        });
    }

    /**
     * @param  Builder<self>  $builder
     */
    public function scopeCleanFromTable(Builder $builder): void
    {
        $builder->where('user_id', auth()->id())
            ->whereNull('message_id')->delete();
    }

    /**
     * @param  Builder<self>  $builder
     * @param  Collection<int, string>|array<int, mixed>  $imgIds
     */
    public function scopeUpdateMessageId(Builder $builder, Collection|array $imgIds, Message $message): void
    {
        $builder->whereIn('id', $imgIds)->update(['message_id' => $message->id]);
    }
}
