<?php

namespace App\Http\Modules\Plans\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Bases\BasePaginateRequest;
use App\Http\Modules\Plans\Models\Plan;
use App\Http\Modules\Plans\Repositories\PlanRepository;
use App\Http\Modules\Plans\Requests\CreatePlanRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Support\Result;

class PlanController extends BaseController
{
    /**
     * PlanController constructor.
     *
     * @param PlanRepository $planRepository
     */
    public function __construct(protected PlanRepository $planRepository) {}

    /**
     * Get all plans active.
     *
     * @return JsonResponse
     * @author Luifer Almendrales, Brandon Torres
     */
    public function allActive()
    {
        try {
            return $this->response(Result::success('Planes listados con exito.', $this->planRepository->getAllPlansActive()));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al listar los planes', message: $th->getMessage()));
        }
    }

    /**
     * Get all plans.
     *
     * @return JsonResponse
     * @author Luifer Almendrales, Brandon Torres
     */
    public function index(BasePaginateRequest $request): JsonResponse
    {
        $limit  = $request->limit ?? 10;
        $search = $request->search ?? '';
        try {
            return $this->response(Result::success('Planes listados con exito.', $this->planRepository->getAllPlans($limit, $search)));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al listar los planes', message: $th->getMessage()));
        }
    }

    /**
     * Get one plan.
     *
     * @param int $id
     * @return JsonResponse
     * @author Luifer Almendrales, Brandon Torres
     */
    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->planRepository->find($id);
            if (!$data) {
                return $this->response(Result::failure(error: 'Plan no encontrado', message: 'Plan no encontrado'));
            }
            return $this->response(Result::success('Plan encontrado con exito.', $data));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al buscar el plan', message: $th->getMessage()));
        }
    }

    /**
     * Create a new plan.
     *
     * @param CreatePlanRequest $request
     * @return JsonResponse
     * @author Luifer Almendrales, Brandon Torres
     */
    public function store(CreatePlanRequest $request): JsonResponse
    {
        try {
            return $this->response(Result::success('Plan creado con exito.', $this->planRepository->save(
                new Plan(array_merge(new CreatePlanRequest($request->validated()), ['user_id' => Auth::user()->id]))
            )));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al crear el plan', message: $th->getMessage()));
        }
    }

    /**
     * Update a plan.
     *
     * @param CreatePlanRequest $request
     * @param int $id
     * @return JsonResponse
     * @author Luifer Almendrales, Brandon Torres
     */
    public function update(CreatePlanRequest $request, int $id): JsonResponse
    {
        try {
            $plan = $this->planRepository->find($id);
            if (!$plan) return $this->response(Result::failure(error: 'Plan no encontrado', message: 'Plan no encontrado'));
            return $this->response(Result::success('Plan actualizado con exito.', $this->planRepository->save($plan->fill($request->validated()))));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al actualizar el plan', message: $th->getMessage()));
        }
    }

    /**
     * Change plan status.
     *
     * @param int $id
     * @return JsonResponse
     * @author Luifer Almendrales, Brandon Torres
     */
    public function changeStatus(int $id): JsonResponse
    {
        try {
            $plan = $this->planRepository->find($id);
            if (!$plan)  return $this->response(Result::failure(error: 'Plan no encontrado', message: 'Plan no encontrado'));
            return $this->response(Result::success('Plan actualizado con exito.', $this->planRepository->save($plan->fill(['is_active' => !$plan->is_active]))));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al cambiar de estado el plan', message: $th->getMessage()));
        }
    }
}
