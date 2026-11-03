<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * The admin panel login. Safe to run repeatedly: it updates the existing account instead of duplicating it.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ziontek.co'],
            [
                'name' => 'ZionTek Admin',
                'password' => '11111118',
                'email_verified_at' => now(),
            ],
        );
    }
}
