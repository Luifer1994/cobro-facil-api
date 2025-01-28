<?php

namespace App\Http\Modules\Users\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Bases\BasePaginateRequest;
use App\Http\Modules\Users\Repositories\UserRepository;
use App\Http\Modules\Users\Requests\ChangePasswordRequest;
use App\Http\Modules\Users\Requests\CreateUserRequest;
use App\Http\Modules\Users\Requests\UpdateUserRequest;
use App\Http\Modules\Users\Services\UserService;
use App\Support\Result;
use Illuminate\Http\JsonResponse;

class UserController extends BaseController
{

    public function __construct(
        protected UserRepository $userRepository,
        protected UserService $userService
    ) {}

    /**
     * Create a new user.
     *
     * @param  CreateUserRequest $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        try {
            $result = $this->userService->register($request);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al crear el registro', message: $th->getMessage()));
        }
    }

    /**
     * Get all users.
     *
     * @param  BasePaginateRequest $request
     * @return JsonResponse
     * @author Luifer Almendrales
     */
    public function index(BasePaginateRequest $request): JsonResponse
    {
        try {
            $limit  = $request->limit ?? 10;
            $search = $request->search ?? '';;
            return $this->response(Result::success('Usuarios obtenidos con éxito', $this->userRepository->getAllUsers($limit, $search)));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener los usuarios', message: $th->getMessage()));
        }
    }

    /**
     * Get user by id.
     *
     * @param  int $id
     * @return JsonResponse
     * @author Luifer Almendrales
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->getById($id);
            if (!$user) return $this->response(Result::failure(error: 'Usuario no encontrado', message: 'Usuario no encontrado'));
            return $this->response(Result::success('Usuario obtenido con éxito', $user));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener el usuario', message: $th->getMessage()));
        }
    }

    /**
     * Update a user.
     *
     * @param  UpdateUserRequest $request
     * @param  int $id (user id)
     * @return JsonResponse
     * @author Luifer Almendrales
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $result = $this->userService->update($request, $id);
           return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al actualizar el usuario', message: $th->getMessage()));
        }
    }

    /**
     * Change password.
     *
     * @param  ChangePasswordRequest $request
     * @return JsonResponse
     * @author Luifer Almendrales
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $result = $this->userService->changePassword($request);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al cambiar la contraseña', message: $th->getMessage()));
        }
    }

    /**
     * Change status.
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function changeStatus(int $id): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);
            if (!$user)
                return $this->response(Result::failure(error: 'Usuario no encontrado', message: 'Usuario no encontrado'));
            return $this->response(Result::success('El estado del usuario ha sido actualizado.', $this->userRepository->save($user->fill(['is_active' => !$user->is_active]))));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al cambiar el estado del usuario', message: $th->getMessage()));
        }
    }
}
