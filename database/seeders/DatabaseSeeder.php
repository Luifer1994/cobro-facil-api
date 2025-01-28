<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                DocumentTypeSeeder::class,
                UserSeeder::class,
                CountrySeeder::class,
                DepartmentSeeder::class,
                PlanSeeder::class,
            ]
        );

        Artisan::call('create-permissions');
    }
}
