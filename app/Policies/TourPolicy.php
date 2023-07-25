<?php

namespace App\Policies;

use App\Models\User;

class TourPolicy
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
}
