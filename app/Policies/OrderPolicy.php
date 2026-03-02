<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function view(User $user, Order $order): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Order $order): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->hasRole('owner');
    }

    public function restore(User $user, Order $order): bool
    {
        return $user->hasRole('owner');
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return $user->hasRole('owner');
    }
}
