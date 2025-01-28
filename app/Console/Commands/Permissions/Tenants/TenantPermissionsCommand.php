<?php

namespace App\Console\Commands\Permissions\Tenants;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantPermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-permission-tenants-module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created all permissions for tenants module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Creando permisos de inquilinos...');

        try {
            $permissions = [
                [
                    'name'        => 'tenants-module',
                    'description' => 'Módulo de inquilinos',
                    'group'       => 'Inquilinos'
                ],
                [
                    "name"        => 'tenants-create',
                    "description" => 'Crear inquilinos',
                    "group"       => 'Inquilinos'
                ],
                [
                    "name"        => 'tenants-list',
                    "description" => 'Listar inquilinos',
                    "group"       => 'Inquilinos'
                ],
                [
                    "name"        => 'tenants-show',
                    "description" => 'Ver inquilinos',
                    "group"       => 'Inquilinos'
                ],
                [
                    "name"        => 'tenants-update',
                    "description" => 'Actualizar inquilinos',
                    "group"       => 'Inquilinos'
                ],
                [
                    "name"        => 'tenants-renovate-plan',
                    "description" => 'Renovar plan de inquilinos',
                    "group"       => 'Inquilinos'
                ],
                [
                    "name"        => 'tenants-change-status',
                    "description" => 'Cambiar estado de inquilinos',
                    "group"       => 'Inquilinos'
                ],
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
