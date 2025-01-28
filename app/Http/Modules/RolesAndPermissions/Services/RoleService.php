<?php

namespace App\Http\Modules\RolesAndPermissions\Services;

use App\Http\Modules\RolesAndPermissions\Repositories\PermissionRepository;
use App\Http\Modules\RolesAndPermissions\Repositories\RoleRepository;
use App\Support\Result;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleService
{
    protected   $RoleRepository;
    protected   $PermissionRepository;

    public function __construct(RoleRepository $RoleRepository, PermissionRepository $PermissionRepository)
    {
        $this->RoleRepository       = $RoleRepository;
        $this->PermissionRepository = $PermissionRepository;
    }

    /**
     * Funtion to asing permissions to a role.
     *
     * @param int $role_id
     * @param array $permissions_ids
     * @return Result
     */
    public function assignPermissionsToRole(int $role_id, array $permissions_ids): Result
    {
        try {
            $role        = $this->RoleRepository->getById($role_id);
            $role->syncPermissions($permissions_ids);
           return Result::success('Permisos asignados correctamente¡', $role);
        } catch (\Throwable $th) {
            return Result::failure('Error al asignar permisos', $th->getMessage());
        }
    }
}
