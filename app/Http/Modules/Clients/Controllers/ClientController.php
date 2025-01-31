<?php

namespace App\Http\Modules\Clients\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Bases\BasePaginateRequest;
use App\Http\Modules\Clients\Repositories\ClientRepository;
use App\Http\Modules\Clients\Requests\CreateClientRequest;
use App\Http\Modules\Clients\Requests\UpdateClientRequest;
use App\Http\Modules\Clients\Services\ClientService;
use App\Support\Result;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ClientController extends BaseController
{
    public function __construct(
        protected ClientRepository $clientRepository,
        protected ClientService $clientService
    ) {}

    public function index(BasePaginateRequest $request): JsonResponse
    {
        try {
            $limit  = $request->limit ?? 10;
            $search = $request->search ?? '';;
            return $this->response(Result::success('clientes obtenidos con éxito', $this->clientRepository->index($limit, $search)));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener los clientes', message: $th->getMessage()));
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $client = $this->clientRepository->findById($id);
            if (!$client) return $this->response(Result::failure(error: 'cliente no encontrado', message: 'cliente no encontrado'));
            return $this->response(Result::success('cliente obtenido con éxito', $client));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener el cliente', message: $th->getMessage()));
        }
    }

    public function store(CreateClientRequest $request): JsonResponse
    {
        try {
            $result = $this->clientService->register($request);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al crear el registro', message: $th->getMessage()));
        }
    }

    public function update(UpdateClientRequest $request, int $id): JsonResponse
    {
        try {
            $result = $this->clientService->update($request, $id);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al actualizar el client', message: $th->getMessage()));
        }
    }

    /**
     * Get all clients.
     *
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            return $this->response(Result::success('Clients obtenidos con éxito', $this->clientRepository->getAll()));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener los clientes', message: $th->getMessage()));
        }
    }
}
