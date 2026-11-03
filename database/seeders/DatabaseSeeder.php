<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Dummy analytics, bookings and leads are never seeded in production.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        if (! app()->isProduction()) {
            $this->call(SiteAnalyticsSeeder::class);
        }
    }
}
