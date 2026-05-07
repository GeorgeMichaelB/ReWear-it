<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'user_id',
        'admin_role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resolveDispute(Dispute $dispute): void
    {
        // Resolve dispute logic
    }

    public function flagListing($item): void
    {
        // Flag listing logic
    }

    public function modifyCommissionRate(string $category, float $rate): void
    {
        // Modify commission rate logic
    }
}