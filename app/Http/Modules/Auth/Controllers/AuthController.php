<?php

namespace App\Http\Modules\Auth\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\Auth\Requests\LoginRequest;
use App\Http\Modules\Auth\Services\AuthService;
use App\Support\Result;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{

    public function __construct(protected AuthService $authService) {}

    /**
     * Function to login a user.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->login($request);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al intentar iniciar sesión', message: $th->getMessage()));
        }
    }

}
