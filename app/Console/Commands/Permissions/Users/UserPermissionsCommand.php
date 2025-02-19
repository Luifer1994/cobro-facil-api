<?php

namespace App\Console\Commands\Permissions\Users;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-permission-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created all permissions for users module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Creando permisos de usuarios...');
        try {
            $permissions = [
                [
                    'name'        => 'users-module',
                    'description' => 'Módulo de usuarios',
                    'group'       => 'Usuarios',
                ],
                [
                    "name"        => 'users-create',
                    "description" => 'Crear usuarios',
                    "group"       => 'Usuarios',
                ],
                [
                    "name"        => 'users-list',
                    "description" => 'Listar usuarios',
                    "group"       => 'Usuarios',
                ],
                [
                    "name"        => 'users-show',
                    "description" => 'Ver usuarios',
                    "group"       => 'Usuarios',
                ],
                [
                    "name"        => 'users-destroy',
                    "description" => 'Eliminar usuarios',
                    "group"       => 'Usuarios',
                ],
                [
                    "name"        => 'users-update',
                    "description" => 'Actualizar usuarios',
                    "group"       => 'Usuarios',
                ],
                [
                    "name"        => 'users-change-password',
                    "description" => 'Cambiar contraseña de usuarios',
                    "group"       => 'Usuarios',
                ],
                [
                    "name"        => 'users-change-status',
                    "description" => 'Cambiar estado de usuarios',
                    "group"       => 'Usuarios',
                ],
            ];
            $role        = Role::where('name', 'super-admin')->first();
            $roleSystem = Role::where('name', 'admin')->first();
            foreach ($permissions as  $value) {
                $permission = Permission::firstOrCreate($value);
                $role->givePermissionTo($permission);
                $roleSystem->givePermissionTo($permission);
            }
            $this->info('Permisos de usuarios creados correctamente');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
