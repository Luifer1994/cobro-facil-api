<?php

namespace App\Http\Modules\RolesAndPermissions\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\RolesAndPermissions\Repositories\RoleRepository;

class RoleController extends BaseController
{

    public function __construct(
        protected RoleRepository $roleRepository
    ) {}
}
