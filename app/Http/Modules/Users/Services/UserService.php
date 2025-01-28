<?php

namespace App\Http\Modules\Users\Services;

use App\Http\Bases\BaseService;
use App\Http\Modules\Users\Repositories\UserRepository;
use App\Http\Modules\Users\Requests\CreateUserRequest;
use App\Http\Modules\RolesAndPermissions\Repositories\RoleRepository;
use App\Http\Modules\Users\Requests\ChangePasswordRequest;
use App\Http\Modules\Users\Requests\UpdateUserRequest;
use App\Support\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    public function __construct(
        private UserRepository $userRepository,
        private RoleRepository $roleRepository
    ) {}

    /**
     * Create a new user.
     *
     * @param CreateUserRequest $request
     * @return Result
     */
    public function register(CreateUserRequest $request): Result
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['password'] = bcrypt($data['password']);
            $user = $this->userRepository->create($data);
            $role = $this->roleRepository->findByName($request->role);
            $user->assignRole($role);
            DB::commit();
            return Result::success(message: 'Registro creado con éxito');
        } catch (\Throwable $th) {
            custom_log($th, __CLASS__);
            DB::rollBack();
            return Result::failure(error: 'Error al crear el registro', message: $th->getMessage());
        }
    }

    /**
     * Update a user
     *
     * @param  UpdateUserRequest $request
     * @param  int $id
     * @return Result
     */
    public function update(UpdateUserRequest $request, int $id): Result
    {
        DB::beginTransaction();

        try {
            $user = $this->userRepository->find($id);

            if ($user) {
                if ($request->password) {
                    $request->merge(['password' => bcrypt($request['password'])]);
                }
                $user->fill($request->all());
                $user   = $this->userRepository->save($user, $request);

                if ($request->role) {
                    $user->syncRoles([]);
                    $roles  = $this->roleRepository->getRolesByNames($request->role);
                    $user->syncRoles($roles);
                }
                DB::commit();
                return Result::success(message: 'Usuario actualizado correctamente');
            } else {
                DB::rollBack();
                return Result::failure(error: 'Usuario no encontrado', message: 'Usuario no encontrado');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            custom_log($th, __CLASS__);
            return Result::failure(error: 'Error al actualizar el usuario', message: $th->getMessage());
        }
    }

    /**
     * Change password of a user.
     *
     * @param  ChangePasswordRequest $request
     * @return Result
     */
    public function changePassword(ChangePasswordRequest $request): Result
    {
        try {
            $user = $this->userRepository->find(Auth::user()->id);

            if ($user) {
                if (password_verify($request->password, $user->password)) {
                    $user->password = bcrypt($request->new_password);
                    $user           = $this->userRepository->save($user);
                    return Result::success(message: 'Contraseña actualizada correctamente');
                } else {
                    return Result::failure(error: 'Contraseña actual incorrecta', message: 'Contraseña actual incorrecta');
                }
            } else {
                return Result::failure(error: 'Usuario no encontrado', message: 'Usuario no encontrado');
            }
        } catch (\Throwable $th) {
            custom_log($th, __CLASS__);
            return Result::failure(error: 'Error al actualizar el usuario', message: $th->getMessage());
        }
    }
}
