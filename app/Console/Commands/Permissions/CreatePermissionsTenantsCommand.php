<?php

namespace App\Console\Commands\Permissions;

use App\Http\Modules\Tenants\Models\Tenant;
use Illuminate\Console\Command;

class CreatePermissionsTenantsCommand extends Command
{
    protected $signature = 'create-permissions-tenants {--all : Crear permisos para todos los tenants}';
    protected $description = 'Crear permisos para uno o todos los tenants';

    /**
     * Execute the console command.
     * 
     * @return void
     */
    public function handle(): void
    {
        $forAll = $this->option('all');

        try {
            if ($forAll) {
                $this->line('Creando permisos para todos los tenants...');
                Tenant::all()->runForEach(function () {
                    $this->createPermissions();
                });
            } else {
                $this->line('Creando permisos para un tenant...');
                if (tenancy()->initialized) $this->createPermissions();
                else $this->error('No se ha inicializado el tenancy');
            }
            $this->info('Permisos creados exitosamente.');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
            $this->line(
                $forAll
                    ? 'No se pudieron crear los permisos para todos los tenants.'
                    : 'No se pudieron crear los permisos.'
            );
        }
    }

    /**
     * Create permissions.
     * 
     * @return void
     */
    private function createPermissions(): void
    {
        $this->call('create-permission-users');
        $this->call('create-permission-roles-and-permissions');
        $this->call('create-permission-cities');
        $this->call('create-permission-document-types');
        $this->call('create-permission-modules');
        $this->call('create-permission-clients');
        $this->call('create-permission-loans');
    }
}
