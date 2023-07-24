<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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

    /** Casts */
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    public function scopePriceFrom(Builder $query, int $price): void
    {
        $query->where('price_in_cents', '>=', $price * 100);
    }

    public function scopePriceTo(Builder $query, int $price): void
    {
        $query->where('price_in_cents', '<=', $price * 100);
    }

    public function scopeDateFrom(Builder $query, Carbon $startDate): void
    {
        $query->where('start_date', '>=', $startDate);
    }

    public function scopeDateTo(Builder $query, Carbon $startDate): void
    {
        $query->where('start_date', '<=', $startDate);
    }

    public function getPriceAttribute(): float
    {
        return (float) $this->price_in_cents / 100;
    }
}
