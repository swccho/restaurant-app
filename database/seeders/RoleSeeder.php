<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Create base roles (owner, staff). Idempotent — safe to run multiple times.
     * Assigns owner role to users who do not yet have a role.
     */
    public function run(): void
    {
        $guard = config('auth.defaults.guard');

        Role::firstOrCreate(['name' => 'owner', 'guard_name' => $guard]);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => $guard]);

        User::query()->each(function (User $user): void {
            if (! $user->hasRole('owner') && ! $user->hasRole('staff')) {
                $user->assignRole('owner');
            }
        });
    }
}
