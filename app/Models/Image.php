<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Log;

class Image extends Model
{
    protected $guarded = false;

    public function getUrlAttribute()
    {
        return url('storage/'.$this['path']);
    }

    public function scopeCleanFromStorage(Builder $builder)
    {
        $paths = $builder->where('user_id', auth()->id())
            ->whereNull('message_id')
            ->get()
            ->pluck('path');

        Log::info($paths);

        $paths->each(function ($path): void {
            Storage::disk('public')->delete($path);
        });
    }

    public function scopeCleanFromTable(Builder $builder)
    {
        $builder->where('user_id', auth()->id())
            ->whereNull('message_id')->delete();
    }

    public function scopeUpdateMessageId(Builder $builder, $imgIds, $message)
    {
        $builder->whereIn('id', $imgIds)->update(['message_id' => $message->id]);
    }
}
