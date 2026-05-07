<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SwapAgreement extends Model
{
    protected $fillable = [
        'transaction_id',
        'party_a_id',
        'party_b_id',
        'cash_top_up_amount',
        'party_a_signed',
        'party_b_signed',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'party_a_signed' => 'boolean',
            'party_b_signed' => 'boolean',
            'cash_top_up_amount' => 'float',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function partyA(): BelongsTo
    {
        return $this->belongsTo(User::class, 'party_a_id');
    }

    public function partyB(): BelongsTo
    {
        return $this->belongsTo(User::class, 'party_b_id');
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'swap_agreement_items');
    }

    public function suggestValueBalancer(): float
    {
        return $this->cash_top_up_amount ?? 0.0;
    }

    public function lockAgreement(): void
    {
        $this->update(['status' => 'locked']);
    }
}