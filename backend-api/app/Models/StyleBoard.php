<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class StyleBoard extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'follower_count',
    ];

    protected function casts(): array
    {
        return [
            'follower_count' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pinnedItems(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'style_board_items');
    }

    public function addPinnedItem(Item $item): void
    {
        $this->pinnedItems()->attach($item->id);
    }
}