<?php

namespace App\Console\Commands\Permissions\Modules;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ModulePermissionsManyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-permission-modules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created all permissions for module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Creando permisos de módulos...');
        try {
            $permissions = [
                [
                    'name'        => 'Ciudades',
                    'description' => 'Acceso permisos de ciudades',
                    'group'       => 'Módulos'
                ],
                [
                    'name'        => 'Panel de control',
                    'description' => 'Acceso permisos de panel de control',
                    'group'       => 'Módulos'
                ],
                [
                    'name'        => 'Tipos de documentos',
                    'description' => 'Acceso permisos de tipos de documentos',
                    'group'       => 'Módulos'
                ],
                [
                    'name'        => 'Módulos',
                    'description' => 'Acceso permisos de módulos',
                    'group'       => 'Módulos'
                ],
                [
                    'name'        => 'Roles y permisos',
                    'description' => 'Acceso permisos de roles y permisos',
                    'group'       => 'Módulos'
                ],
                [
                    'name'        => 'Usuarios',
                    'description' => 'Acceso permisos de usuarios',
                    'group'       => 'Módulos'
                ],
                [

                    'name'        => 'Inquilinos',
                    'description' => 'Acceso permisos de inquilinos',
                    'group'       => 'Módulos'
                ]
            ];
            $role        = Role::where('name', 'super-admin')->first();
            $roleSystem = Role::where('name', 'admin')->first();

            foreach ($permissions as  $value) {
                $permission = Permission::firstOrCreate($value);
                $role->givePermissionTo($permission);
                $roleSystem->givePermissionTo($permission);
            }
            $this->info('Permisos creados correctamente.');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
