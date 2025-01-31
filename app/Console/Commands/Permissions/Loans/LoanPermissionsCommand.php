<?php

namespace App\Console\Commands\Permissions\Loans;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LoanPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-permission-loans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created all permissions for loans module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Creando permisos de Prestamos...');
        try {
            $permissions = [
                [
                    'name'        => 'loans-module',
                    'description' => 'Módulo de Prestamos',
                    'group'       => 'Prestamos',
                ],
                [
                    "name"        => 'loans-create',
                    "description" => 'Crear Prestamos',
                    "group"       => 'Prestamos',
                ],
                [
                    "name"        => 'loans-list',
                    "description" => 'Listar Prestamos',
                    "group"       => 'Prestamos',
                ],
                [
                    "name"        => 'loans-show',
                    "description" => 'Ver Prestamos',
                    "group"       => 'Prestamos',
                ],
                [
                    "name"        => 'loans-update',
                    "description" => 'Actualizar Prestamos',
                    "group"       => 'Prestamos',
                ],
                [
                    "name"        => 'loans-list-all',
                    "description" => 'Listar todos los Prestamos',
                    "group"       => 'Prestamos',
                ],
            ];
            $role        = Role::where('name', 'super-admin')->first();
            $roleSystem = Role::where('name', 'admin')->first();
            foreach ($permissions as  $value) {
                $permission = Permission::firstOrCreate($value);
                $role->givePermissionTo($permission);
                $roleSystem->givePermissionTo($permission);
            }
            $this->info('Permisos de Prestamos creados correctamente');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
