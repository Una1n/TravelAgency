<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    use HasFactory;

    /** Fillable */
    protected $fillables = [
        'id',
        'is_public',
        'slug',
        'description',
        'number_of_days',
        'name',
    ];
}