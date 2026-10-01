<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $guarded = false;

    public function branches()
    {
        return $this->hasMany(Branch::class, 'section_id', 'id');
    }
}
