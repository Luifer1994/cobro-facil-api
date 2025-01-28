<?php

namespace App\Http\Modules\RolesAndPermissions\Repositories;

use App\Http\Bases\BaseRepository;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository
{
    function __construct(protected Permission $modelPermissions) {
            parent::__construct($modelPermissions);
    }
}
