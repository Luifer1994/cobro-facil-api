<?php

namespace App\Http\Modules\Cities\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Bases\BasePaginateRequest;
use App\Http\Modules\Cities\Repositories\CityRepository;
use App\Http\Modules\Cities\Requests\ListCitiesByDepartmentRequest;
use App\Support\Result;
use Illuminate\Http\JsonResponse;

class CityController extends BaseController
{

    public function __construct(
        protected CityRepository $cityRepository
    ) {}

    /**
     * List cities by department.
     *
     * @param ListCitiesByDepartmentRequest $request
     * @return JsonResponse
     */
    public function listByDepartment(ListCitiesByDepartmentRequest $request): JsonResponse
    {
        try {
            return $this->response(Result::success(message: 'Recursos obtenidos correctamente', value: $this->cityRepository->listByDepartment($request->route('departmentId'))));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener los recursos', message: $th->getMessage()));
        }
    }

    /**
     * Get all document types.
     *
     * @param  BasePaginateRequest $request
     * @return JsonResponse
     */
    public function index(BasePaginateRequest $request): JsonResponse
    {
        try {
            $limit  = $request->limit ?? 10;
            $search = $request->search ?? '';

            return $this->response(Result::success('Ciudades obtenidas con éxito', $this->cityRepository->getCities($limit, $search)));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al listar las ciudades', message: $th->getMessage()));
        }
    }

    /**
     * Get all cities.
     *
     * @param  BasePaginateRequest $request
     * @return JsonResponse
     */
    public function all(BasePaginateRequest $request): JsonResponse
    {
        try {
            $limit  = $request->limit ?? 30;
            $search = $request->search ?? '';
            return $this->response(Result::success('Ciudades obtenidas con éxito', $this->cityRepository->getAllCities($limit, $search)));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al listar las ciudades', message: $th->getMessage()));
        }
    }
}
