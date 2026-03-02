<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\User;

class OfferPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function view(User $user, Offer $offer): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function create(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function update(User $user, Offer $offer): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function delete(User $user, Offer $offer): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function restore(User $user, Offer $offer): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function forceDelete(User $user, Offer $offer): bool
    {
        return $user->hasRole('owner');
    }
}
