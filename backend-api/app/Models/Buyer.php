<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Buyer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_info',
    ];

    protected function casts(): array
    {
        return [
            'payment_info' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('type', 'shipping')->where('is_default', true);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function addToCart($item): void
    {
        // Cart logic - could be stored in session, DB, or cache
    }

    public function checkout(): Order
    {
        // Checkout logic - create order from cart items
    }

    public function proposeSwap(): SwapAgreement
    {
        // Swap proposal logic
    }

    public function leaveReview(User $user, array $reviewData): void
    {
        // Review logic
    }
}