<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTour
 */
class Tour extends Model
{
    use HasFactory, HasUuids;

    /** Fillables */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'price',
    ];

    /** Casts */
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    /**
     * @return BelongsTo<Travel,Tour>
     */
    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    /**
     * @param  Builder<Model>  $query
     */
    public function scopePriceFrom(Builder $query, string $price): void
    {
        $query->where('price', '>=', (float) $price * 100);
    }

    /**
     * @param  Builder<Model>  $query
     */
    public function scopePriceTo(Builder $query, string $price): void
    {
        $query->where('price', '<=', (float) $price * 100);
    }

    /**
     * @param  Builder<Model>  $query
     */
    public function scopeDateFrom(Builder $query, Carbon $startDate): void
    {
        $query->where('start_date', '>=', $startDate);
    }

    /**
     * @param  Builder<Model>  $query
     */
    public function scopeDateTo(Builder $query, Carbon $startDate): void
    {
        $query->where('start_date', '<=', $startDate);
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }
}
