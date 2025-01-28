<?php

namespace Database\Seeders;

use App\Http\Modules\Plans\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'name' => 'Basic',
            'description' => 'Plan básico de 6 meses',
            'price' => 20000,
            'number_of_month' => 6,
            'is_active' => true,
            'user_id' => 1
        ]);

        Plan::create([
            'name' => 'Premium',
            'description' => 'Plan premium de 12 meses',
            'price' => 40000,
            'number_of_month' => 12,
            'is_active' => true,
            'user_id' => 1
        ]);

        Plan::create([
            'name' => 'Enterprise',
            'description' => 'Plan enterprise de 24 meses',
            'price' => 80000,
            'number_of_month' => 24,
            'is_active' => true,
            'user_id' => 1
        ]);
    }
}
