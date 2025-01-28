<?php

namespace App\Http\Modules\Clients\Services;

use App\Http\Modules\Clients\Repositories\ClientRepository;
use App\Http\Bases\BaseService;
use App\Http\Modules\Clients\Requests\CreateClientRequest;
use App\Http\Modules\Clients\Requests\UpdateClientRequest;
use App\Support\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientService extends BaseService
{
    /**
     * ClientService constructor.
     *
     * @param  ClientRepository $clientRepository
     */
    public function __construct(protected ClientRepository $clientRepository)
    {
        //
    }

    public function register(CreateClientRequest $request): Result
    {
        DB::beginTransaction();
        try {
            $this->clientRepository->create(array_merge($request->validated(), [
                'user_id' => Auth::user()->id
            ]));
            DB::commit();
            return Result::success(message: 'Registro creado con éxito');
        } catch (\Throwable $th) {
            custom_log($th, __CLASS__);
            DB::rollBack();
            return Result::failure(error: 'Error al crear el registro', message: $th->getMessage());
        }
    }

    public function update(UpdateClientRequest $request, int $id): Result
    {
        DB::beginTransaction();
        try {
            $user = $this->clientRepository->find($id);
            if ($user) {
                $user->fill($request->validated());
                $this->clientRepository->save($user, $request);
                DB::commit();
                return Result::success(message: 'Cliente actualizado correctamente');
            } else {
                DB::rollBack();
                return Result::failure(error: 'Cliente no encontrado', message: 'Cliente no encontrado');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            custom_log($th, __CLASS__);
            return Result::failure(error: 'Error al actualizar el Cliente', message: $th->getMessage());
        }
    }
}
