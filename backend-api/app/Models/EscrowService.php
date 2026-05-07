<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EscrowService extends Model
{
    protected $fillable = [
        'sale_order_id',
        'held_amount',
        'release_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'held_amount' => 'float',
            'release_date' => 'datetime',
        ];
    }

    public function saleOrder(): BelongsTo
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function lockFunds(float $amount): bool
    {
        $this->update([
            'held_amount' => $amount,
            'status' => 'held',
        ]);
        return true;
    }

    public function releaseToSeller(int $sellerId): void
    {
        $this->update(['status' => 'released']);
    }

    public function refundToBuyer(int $buyerId): void
    {
        $this->update(['status' => 'refunded']);
    }
}