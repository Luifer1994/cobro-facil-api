<?php

namespace App\Console\Commands\Permissions\Plans;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PlanPermissionsManyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-permission-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created all permissions for plans module';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Creando permisos de planes...');
        try {
            $permissions = [
                [
                    'name'        => 'plans-module',
                    'description' => 'Módulo de planes',
                    'group'       => 'Planes'
                ],
                [
                    "name"        => 'plans-create',
                    "description" => 'Crear planes',
                    "group"       => 'Planes'
                ],
                [
                    "name"        => 'plans-list',
                    "description" => 'Listar planes',
                    "group"       => 'Planes'
                ],
                [
                    "name"        => 'plans-show',
                    "description" => 'Ver planes',
                    "group"       => 'Planes'
                ],
                [
                    "name"        => 'plans-update',
                    "description" => 'Actualizar planes',
                    "group"       => 'Planes'
                ],
                [
                    "name"        => 'plans-list-actives',
                    "description" => 'Listar planes activos',
                    "group"       => 'Planes'
                ],
                [
                    "name"        => 'plans-change-status',
                    "description" => 'Cambiar estado de planes',
                    "group"       => 'Planes'
                ]
            ];
            $role        = Role::where('name', 'super-admin')->first();
            $roleSystem = Role::where('name', 'admin')->first();

            foreach ($permissions as  $value) {
                $permission = Permission::firstOrCreate($value);
                $role->givePermissionTo($permission);
                $roleSystem->givePermissionTo($permission);
            }
            $this->info('Permisos de planes creados correctamente');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
