<?php

namespace App\Http\Modules\Clients\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Clients\Models\ClientLocation;

class ClientLocationRepository extends BaseRepository
{
    /**
     * ClientLocationRepository constructor.
     *
     * @param  ClientLocation $clientLocation
     */
    public function __construct(protected ClientLocation $clientLocation)
    {
        parent::__construct($clientLocation);
    }
}