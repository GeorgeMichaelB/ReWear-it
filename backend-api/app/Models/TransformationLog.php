<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransformationLog extends Model
{
    protected $fillable = [
        'item_id',
        'creator_id',
        'before_image_url',
        'after_image_url',
        'modifications_made',
    ];

    protected function casts(): array
    {
        return [
            'modifications_made' => 'array',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Creator::class);
    }

    public function generateCareInstructions(): string
    {
        return "Hand wash recommended. Do not bleach.";
    }
}