<?php

namespace App\Policies;

use App\Models\Travel;
use App\Models\User;

class TravelPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Travel $travel): bool
    {
        return $user->isEditor();
    }
}
