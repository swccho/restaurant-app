<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function view(User $user, Category $category): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function create(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function update(User $user, Category $category): bool
    {
        return $user->canAccessAdminPanel();
    }

    public function delete(User $user, Category $category): bool
    {
        return $user->hasRole('owner');
    }

    public function restore(User $user, Category $category): bool
    {
        return $user->hasRole('owner');
    }

    public function forceDelete(User $user, Category $category): bool
    {
        return $user->hasRole('owner');
    }
}
