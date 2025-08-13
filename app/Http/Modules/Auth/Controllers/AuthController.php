<?php

namespace App\Http\Modules\Auth\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\Auth\Requests\LoginRequest;
use App\Http\Modules\Auth\Requests\LoginWithTenantRequest;
use App\Http\Modules\Auth\Requests\ValidateEmailForLoginRequest;
use App\Http\Modules\Auth\Services\AuthService;
use App\Http\Modules\Auth\Services\ValidateEmialForLoginService;
use App\Support\Result;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{

    public function __construct(protected AuthService $authService, protected ValidateEmialForLoginService $validateEmialForLoginService) {}

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

    /**
     * Function to logout a user.
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $result = $this->authService->logout();
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al intentar cerrar sesión', message: $th->getMessage()));
        }
    }

    public function validateEmail(ValidateEmailForLoginRequest $request): JsonResponse
    {
        try {
            $result = $this->validateEmialForLoginService->execute($request);
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al intentar validar correo electrónico '.$th->getMessage(), message: $th->getMessage()));
        }
    }

    public function loginWithTenant(LoginWithTenantRequest $request): JsonResponse
    {
        try {
            tenancy()->initialize($request->tenant_id);
            $result = $this->authService->login(new LoginRequest([
                'email' => $request->email,
                'password' => $request->password
            ]));
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al intentar iniciar sesión', message: $th->getMessage()));
        }
    }
}
