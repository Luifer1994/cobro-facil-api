<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(
            [
                UserTenantSeeder::class,
                DocumentTypeSeeder::class,
                CountrySeeder::class,
                DepartmentSeeder::class,
            ]
        );

        Artisan::call('create-permissions-tenants');
    }
}
