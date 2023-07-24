<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;

    /** Fillable */
    protected $fillable = [
        'id',
        'is_public',
        'slug',
        'description',
        'number_of_days',
        'name',
    ];

    protected static function booted(): void
    {
        static::creating(function (Travel $travel) {
            $travel->slug = str($travel->name)->slug()->toString();
        });
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
