<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ThemeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @use HasFactory<ThemeFactory>
 **/
class Theme extends Model
{
    /**  @use HasFactory<ThemeFactory> **/
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * @return HasMany<Message, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'theme_id', 'id');
    }
}
