<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Create owner and staff roles, assign owner to existing admin users.
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
