<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'seller_verification_status',
        'payout_wallet_address',
        'total_sales',
    ];

    protected function casts(): array
    {
        return [
            'total_sales' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): HasOne
    {
        return $this->hasOne(Creator::class);
    }

    public function listItem($item): void
    {
        // List item logic - create product
    }

    public function acceptSwap(SwapAgreement $swap): void
    {
        // Accept swap logic
    }

    public function generateShippingLabel(SaleOrder $order): Label
    {
        // Generate shipping label logic
        return new Label();
    }
}