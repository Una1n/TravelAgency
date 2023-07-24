<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tour extends Model
{
    use HasFactory, HasUuids;

    /** Fillables */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'price_in_cents',
    ];

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }
    public function getPriceAttribute(): float
    {
        return (float) $this->price_in_cents / 100;
    }
}
