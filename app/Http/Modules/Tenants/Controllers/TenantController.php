<?php

namespace App\Http\Modules\Tenants\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Bases\BasePaginateRequest;
use App\Http\Modules\Tenants\Repositories\TenantRepository;
use App\Http\Modules\Tenants\Requests\CreateTenantRequest;
use App\Http\Modules\Tenants\Requests\RenewTenantPlanRequest;
use App\Http\Modules\Tenants\Requests\UpdateTenantRequest;
use App\Http\Modules\Tenants\Services\TenantService;
use App\Support\Result;
use Illuminate\Http\JsonResponse;

class TenantController extends BaseController
{
    public function __construct(
        protected TenantService $tenantService,
        protected TenantRepository $tenantRepository
    ) {}

    /**
     * Create a new tenant.
     *
     * @param CreateTenantRequest $request
     * @return JsonResponse
     */
    public function store(CreateTenantRequest $request): JsonResponse
    {
        try {
            $result = $this->tenantService->createTenant($request);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al crear el registro ' . $th->getMessage(), message: $th->getMessage()));
        }
    }

    /**
     * Get all tenants.
     *
     * @param BasePaginateRequest $request
     * @return JsonResponse
     */
    public function index(BasePaginateRequest $request): JsonResponse
    {
        try {
            $result = $this->tenantService->getAllTenants(new BasePaginateRequest($request->validated()));
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener los tenants', message: $th->getMessage()));
        }
    }

    /**
     * Get restaurant by id.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $result = $this->tenantService->getTenantById($id);

            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener el inquilino', message: $th->getMessage()));
        }
    }

    /**
     * Validate restaurant by id.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function validateActiveTenant(string $id): JsonResponse
    {
        try {
            $result = $this->tenantService->validateActiveTenant($id);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al validar el inquilino', message: $th->getMessage()));
        }
    }

    /**
     * Renew restaurant plan.
     *
     * @param RenewTenantPlanRequest $request
     * @return JsonResponse
     */
    public function renovatePlan(RenewTenantPlanRequest $request): JsonResponse
    {
        try {
            $result = $this->tenantService->renewTenantPlan(new RenewTenantPlanRequest($request->validated()));
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al renovar el plan', message: $th->getMessage()));
        }
    }

    /**
     * Update restaurant by id.
     *
     * @param UpdateTenantRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateTenantRequest $request, string $id): JsonResponse
    {
        try {

            $validatedData = $request->validated();
            $newRequest = new UpdateTenantRequest($validatedData);

            if ($request->hasFile('logo')) {
                $newRequest->files->set('logo', $request->file('logo'));
            }

            $result = $this->tenantService->updateTenant($newRequest, $id);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al actualizar el inquilino ' . $th->getMessage(), message: $th->getMessage()));
        }
    }

    /**
     * Change restaurant status.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function changeStatus(string $id): JsonResponse
    {
        try {
            $tenant = $this->tenantRepository->find($id);
            if (!$tenant)
                return $this->response(Result::failure(error: 'El inquilino no existe', message: 'El inquilino no existe'));
            $this->tenantRepository->save($tenant->fill(['is_active' => !$tenant->is_active]));
            return $this->response(Result::success('El estado del inquilino ha sido actualizado.', $tenant));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al cambiar el estado del inquilino', message: $th->getMessage()));
        }
    }
}
