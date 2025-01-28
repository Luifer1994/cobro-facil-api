<?php

namespace App\Console\Commands\Permissions;

use Illuminate\Console\Command;

class CreatePermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Created all permissions';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $this->call('create-permission-users');
            $this->call('create-permission-tenants-module');
            $this->call('create-permission-modules');
            $this->call('create-permission-roles-and-permissions');
            $this->call('create-permission-cities');
            $this->call('create-permission-document-types');
            $this->call('create-permission-plans');
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
