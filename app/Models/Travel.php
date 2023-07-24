<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Travel extends Model
{
    use HasFactory, HasUuids;

    /** Fillable */
    protected $fillable = [
        'is_public',
        'name',
        'description',
        'number_of_days',
    ];

    protected static function booted(): void
    {
        static::creating(function (Travel $travel) {
            $slug = str($travel->name)->slug()->toString();
            $count = Travel::query()->where('slug', 'like', $slug . '-%')
                ->orWhere('slug', '=', $slug)
                ->count();

            if ($count > 0) {
                $slug .= '-' . $count;
            }

            $travel->slug = $slug;
        });
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    public function getPriceAttribute(): float
    {
        return $this->price / 100;
    }

    // What is used for model id when using it in routes
    public function getRouteKey(): string
    {
        return $this->slug;
    }
}
