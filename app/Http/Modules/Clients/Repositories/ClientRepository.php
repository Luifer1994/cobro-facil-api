<?php

namespace App\Http\Modules\Clients\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Clients\Models\Client;

class ClientRepository extends BaseRepository
{
    /**
     * ClientRepository constructor.
     *
     * @param  Client $client
     */
    public function __construct(protected Client $client)
    {
        parent::__construct($client);
    }
}