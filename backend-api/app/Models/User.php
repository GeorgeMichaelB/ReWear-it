<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar', 'phone',
        'trust_score', 'eco_credits', 'preferred_currency',
        'size', 'aesthetic_preference', 'upcycle_intensity',
        'is_pro_upcycler', 'pro_upcycler_badges', 'eco_verified',
    ];

    protected $casts = [
        'trust_score' => 'float',
        'eco_credits' => 'integer',
        'is_pro_upcycler' => 'boolean',
        'eco_verified' => 'boolean',
        'pro_upcycler_badges' => 'array',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'trust_score' => 'float',
            'eco_credits' => 'integer',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function buyer(): HasOne
    {
        return $this->hasOne(Buyer::class);
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class);
    }

    public function login(): bool
    {
        return true;
    }

    public function logout(): void
    {
        $this->currentAccessToken()->delete();
    }
}