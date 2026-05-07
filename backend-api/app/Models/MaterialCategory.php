<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialCategory extends Model
{
    protected $fillable = [
        'fabric_name',
        'is_organic',
        'recycle_tier',
    ];

    protected function casts(): array
    {
        return [
            'is_organic' => 'boolean',
            'recycle_tier' => 'integer',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}