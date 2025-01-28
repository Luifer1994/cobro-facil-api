<?php

namespace App\Http\Modules\RolesAndPermissions\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Bases\BasePaginateRequest;
use App\Http\Modules\RolesAndPermissions\Repositories\RoleRepository;
use App\Http\Modules\RolesAndPermissions\Requests\AsingPermissionsToRoleRequest;
use App\Http\Modules\RolesAndPermissions\Requests\CreateRoleRequest;
use App\Http\Modules\RolesAndPermissions\Requests\UpdateRoleRequest;
use App\Http\Modules\RolesAndPermissions\Services\RoleService;
use App\Support\Result;
use Illuminate\Http\JsonResponse;

class RoleController extends BaseController
{

    public function __construct(
        protected RoleRepository $roleRepository,
        protected RoleService $roleService
    ) {}


    public function index(BasePaginateRequest $request): JsonResponse
    {
        try {
            $search = $request->search ?? '';
            $limit = $request->limit ?? 10;
            /*  return $this->successResponse($this->roleRepository->getPaginated($search, $limit), 'ok', Response::HTTP_OK); */
            return $this->response(Result::success('Roles obtenidos con éxito', $this->roleRepository->getPaginated($search, $limit)));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener los roles', message: $th->getMessage()));
        }
    }


    public function listAll(): JsonResponse
    {
        try {
            return $this->response(Result::success('Roles obtenidos con éxito', $this->roleRepository->getAll()));
        } catch (\Throwable $th) {
            custom_log($th, __CLASS__);
            return $this->response(Result::failure(error: 'Error al obtener los roles', message: $th->getMessage()));
        }
    }


    public function show(int $id): JsonResponse
    {
        try {
            $role = $this->roleRepository->getById($id);
            if (!$role) return $this->response(Result::failure(error: 'No se encontró el rol', message: 'No se encontró el rol'));
            return $this->response(Result::success('Role obtenido con éxito', $role));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener el rol', message: $th->getMessage()));
        }
    }



    public function assignPermissionsToRole(AsingPermissionsToRoleRequest $request): JsonResponse
    {
        try {
            $result = $this->roleService->assignPermissionsToRole($request->role_id, $request->permissions, $request->group);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al asignar permisos', message: $th->getMessage()));
        }
    }


    public function store(CreateRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleRepository->create($request->all());
            return $this->response(Result::success('Rol creado con éxito', $role->data));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al crear el rol', message: $th->getMessage()));
        }
    }


    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        try {
            $role = $this->roleRepository->getById($id);
            if (!$role)
                return $this->response(Result::failure(error: 'No se encontró el rol', message: 'No se encontró el rol'));

            $role = $this->roleRepository->update($id, $request->validated());
            return $this->response(Result::success('Rol actualizado con éxito', $role));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al actualizar el rol', message: $th->getMessage()));
        }
    }
}
