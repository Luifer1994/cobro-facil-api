<?php

namespace App\Http\Modules\Tenants\Services;

use App\Http\Bases\BaseService;
use App\Http\Modules\Tenants\Repositories\TemporalTenantRepository;

class ApprovedTemporalTenantService extends BaseService
{
    /**
     * ApprovedTemporalTenantService constructor.
     *
     * @param  TemporalTenantRepository $temporalTenantRepository
     */
    public function __construct(protected TemporalTenantRepository $temporalTenantRepository)
    {
        //
    }

    // Aquí puedes añadir métodos propios de la capa de servicio
}