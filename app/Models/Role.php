<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @mixin IdeHelperRole
 */
class Role extends Model
{
    use HasFactory, HasUuids;

    /** Fillable */
    protected $fillable = [
        'name',
    ];

    /**
     * @return BelongsToMany<User,Role>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
