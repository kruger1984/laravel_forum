<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    protected $guarded = false;

    public function children(): HasMany
    {
        return $this->hasMany(Branch::class, 'parent_id', 'id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'parent_id', 'id');
    }
}
