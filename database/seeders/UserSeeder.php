<?php

namespace Database\Seeders;

use App\Http\Modules\Users\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'super-admin', 'description'  => 'Super Admin']);
        Role::firstOrCreate(['name' => 'admin', 'description'  => 'Administrador']);
        $users = [
            [
                'name' => 'Luis',
                'email' => 'almendralesluifer@gmail.com',
                'is_active' => true,
                'password' => bcrypt('1004280446'),
            ]
        ];

        foreach ($users as $value) {
            $user = User::create($value);
            $user->assignRole($role);
        }
    }
}
