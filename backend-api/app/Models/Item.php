<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'seller_id',
        'category_id',
        'title',
        'description',
        'price',
        'condition',
        'status',
        'carbon_savings',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'carbon_savings' => 'float',
        ];
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transformationLogs(): HasMany
    {
        return $this->hasMany(TransformationLog::class);
    }

    public function styleBoards(): HasMany
    {
        return $this->hasMany(StyleBoard::class);
    }

    public function materialCategory(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class);
    }

    public function updateStatus(string $newStatus): void
    {
        $this->update(['status' => $newStatus]);
    }

    public function calculateCarbonSavings(): float
    {
        return $this->carbon_savings ?? 0.0;
    }
}