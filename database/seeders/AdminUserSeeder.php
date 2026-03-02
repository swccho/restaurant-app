<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Create or update owner and staff admin users from env.
     * Safe to run multiple times (updateOrCreate by email).
     */
    public function run(): void
    {
        $ownerEmail = env('ADMIN_OWNER_EMAIL');
        $ownerPassword = env('ADMIN_OWNER_PASSWORD');
        $staffEmail = env('ADMIN_STAFF_EMAIL');
        $staffPassword = env('ADMIN_STAFF_PASSWORD');

        if (filled($ownerEmail) && filled($ownerPassword)) {
            $owner = User::query()->updateOrCreate(
                ['email' => $ownerEmail],
                [
                    'name' => 'Owner',
                    'password' => Hash::make($ownerPassword),
                ]
            );
            $owner->syncRoles(['owner']);
        }

        if (filled($staffEmail) && filled($staffPassword)) {
            $staff = User::query()->updateOrCreate(
                ['email' => $staffEmail],
                [
                    'name' => 'Staff',
                    'password' => Hash::make($staffPassword),
                ]
            );
            $staff->syncRoles(['staff']);
        }
    }
}
