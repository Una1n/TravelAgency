<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    /** Fillables */
    protected $fillables = [
        'name',
        'start_date',
        'end_date',
        'price',
    ];
}
