<?php

namespace App\Http\Modules\Clients\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\Clients\Repositories\ClientRepository;
use App\Http\Modules\Clients\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ClientController extends BaseController
{
    /**
     * ClientController constructor.
     *
     * @param  ClientRepository $clientRepository
     * @param  ClientService $clientService
     */
    public function __construct(
        protected ClientRepository $clientRepository,
        protected ClientService $clientService
    ) {
        //
    }

    // Aquí puedes añadir tus métodos (index, show, store, update, destroy, etc.)
}