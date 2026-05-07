<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleOrder extends Model
{
    protected $fillable = [
        'transaction_id',
        'item_id',
        'total_amount',
        'platform_fee',
        'tracking_number',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'float',
            'platform_fee' => 'float',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function escrowService(): HasOne
    {
        return $this->hasOne(EscrowService::class);
    }

    public function calculateDynamicFee(): float
    {
        return $this->total_amount * 0.10; // 10% dynamic fee
    }

    public function applyBundleDiscount(): float
    {
        // Bundle discount logic
        return $this->total_amount;
    }
}