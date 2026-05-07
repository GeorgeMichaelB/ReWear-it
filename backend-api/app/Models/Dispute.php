<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dispute extends Model
{
    protected $fillable = [
        'transaction_id',
        'reporter_id',
        'reason',
        'evidence_photos',
        'resolution_status',
        'verdict',
    ];

    protected function casts(): array
    {
        return [
            'evidence_photos' => 'array',
        ];
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function uploadEvidence(string $photoURL): void
    {
        $photos = $this->evidence_photos ?? [];
        $photos[] = $photoURL;
        $this->update(['evidence_photos' => $photos]);
    }

    public function closeDispute(string $verdict): void
    {
        $this->update([
            'resolution_status' => 'closed',
            'verdict' => $verdict,
        ]);
    }
}