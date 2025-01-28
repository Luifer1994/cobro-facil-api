<?php

namespace App\Http\Modules\Clients\Services;

use App\Http\Modules\Clients\Repositories\ClientRepository;
USE App\Http\Bases\BaseService;

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

    // Aquí puedes añadir métodos propios de la capa de servicio
}