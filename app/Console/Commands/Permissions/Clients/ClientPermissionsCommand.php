<?php

namespace App\Console\Commands\Permissions\clients;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ClientPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-permission-clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created all permissions for clients module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Creando permisos de Clientes...');
        try {
            $permissions = [
                [
                    'name'        => 'clients-module',
                    'description' => 'Módulo de Clientes',
                    'group'       => 'Clientes',
                ],
                [
                    "name"        => 'clients-create',
                    "description" => 'Crear Clientes',
                    "group"       => 'Clientes',
                ],
                [
                    "name"        => 'clients-list',
                    "description" => 'Listar Clientes',
                    "group"       => 'Clientes',
                ],
                [
                    "name"        => 'clients-show',
                    "description" => 'Ver Clientes',
                    "group"       => 'Clientes',
                ],
                [
                    "name"        => 'clients-destroy',
                    "description" => 'Eliminar Clientes',
                    "group"       => 'Clientes',
                ],
                [
                    "name"        => 'clients-update',
                    "description" => 'Actualizar Clientes',
                    "group"       => 'Clientes',
                ]
            ];
            $role        = Role::where('name', 'super-admin')->first();
            $roleSystem = Role::where('name', 'admin')->first();
            foreach ($permissions as  $value) {
                $permission = Permission::firstOrCreate($value);
                $role->givePermissionTo($permission);
                $roleSystem->givePermissionTo($permission);
            }
            $this->info('Permisos de clientes creados correctamente');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
