<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @use HasFactory<SectionFactory>
 **/
class Section extends Model
{
    /** @use HasFactory<SectionFactory> **/
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    /**
     * @return HasMany<Branch, $this>
     */
    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'section_id', 'id');
    }

    /**
     * @return HasMany<Branch, $this>
     */
    public function parentBranches(): HasMany
    {
        return $this->hasMany(Branch::class, 'section_id', 'id')
            ->whereNull('parent_id');
    }
}
