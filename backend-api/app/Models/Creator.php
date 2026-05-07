<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Creator extends Model
{
    protected $fillable = [
        'seller_id',
        'pro_upcycle_badge',
        'total_waste_diverted',
    ];

    protected function casts(): array
    {
        return [
            'pro_upcycle_badge' => 'boolean',
            'total_waste_diverted' => 'float',
        ];
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function transformationLogs(): HasMany
    {
        return $this->hasMany(TransformationLog::class);
    }

    public function logTransformation(Item $item): TransformationLog
    {
        return TransformationLog::create([
            'item_id' => $item->id,
            'creator_id' => $this->seller->user_id,
        ]);
    }

    public function createBulkListing(array $collection): void
    {
        // Bulk listing creation logic
    }
}