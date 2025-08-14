<?php

namespace App\Http\Modules\Tenants\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Tenants\Models\TemporalTenant;

class TemporalTenantRepository extends BaseRepository
{
    /**
     * TemporalTenantRepository constructor.
     *
     * @param  TemporalTenant $temporalTenant
     */
    public function __construct(protected TemporalTenant $temporalTenant)
    {
        parent::__construct($temporalTenant);
    }

    public function create(array $data): TemporalTenant
    {
        return $this->temporalTenant->create($data);
    }
}