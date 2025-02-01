<?php

namespace App\Http\Modules\Auth\Services;

use App\Http\Bases\BaseService;
use App\Support\Result;
use App\Http\Modules\Auth\Requests\LoginRequest;
use App\Http\Modules\Users\Models\User;
use App\Http\Modules\Users\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService extends BaseService
{

    public function __construct(protected UserRepository $userRepository) {}

    /**
     * Function to login user.
     *
     * @param LoginRequest $request
     * @return Result
     */
    public function login(LoginRequest $request): Result
    {
        try {
            $user = $this->userRepository->getByEmail($request->email);

            if (!$user || !Hash::check($request->password, $user->password))
                return Result::failure('Credenciales incorrectas');

            $token = $user->createToken('auth_token')->plainTextToken;
            $roles = $user->roles->pluck('name');
            $tenant = tenancy()->tenant;
            $limitedData = $tenant ? $tenant->toLimitedArray() : null;
            $validatePermissions = $this->getUserPermissions($user);

            $userLogged = [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'roles'       => base64_encode(json_encode($roles)),
                'permissions' => base64_encode(json_encode($validatePermissions)),
                'domain_type' => tenancy()->initialized
                    ? base64_encode('tenant')
                    : base64_encode('central'),
            ];

            return Result::success('Inicio de sesión exitoso', [
                'token'  => $token,
                'user'   => $userLogged,
                'tenant' => $limitedData
            ]);
        } catch (\Throwable $th) {
            custom_log($th, __CLASS__);
            return Result::failure('Error al intentar iniciar sesión', $th->getMessage());
        }
    }

    /**
     * Function to get user permissions.
     *
     * @param User $user
     * @return array
     */
    protected function getUserPermissions(User $user): array
    {
        if (!tenancy()->initialized)  return $user->getAllPermissions()->pluck('name')->toArray();

        $permissionsGroup = $user->getAllPermissions()->pluck('name')
            ->unique()
            ->values()
            ->toArray();

        $permissions = $user->getAllPermissions();
        $validatePermissions = [];

        foreach ($permissions as $value) if (in_array($value->group, $permissionsGroup)) $validatePermissions[] = $value->name;

        return array_merge($validatePermissions, $permissionsGroup);
    }

    /**
     * Function to logout user.
     *
     * @return Result
     */
    public function logout(): Result
    {
        try {
            auth()->user()->tokens()->delete();
            return Result::success('Sesión cerrada exitosamente');
        } catch (\Throwable $th) {
            custom_log($th, __CLASS__);
            return Result::failure('Error al intentar cerrar sesión', $th->getMessage());
        }
    }
}
