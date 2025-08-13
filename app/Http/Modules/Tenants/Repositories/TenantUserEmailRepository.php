<?php

namespace App\Http\Modules\Tenants\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Tenants\Models\TenantUserEmail;

class TenantUserEmailRepository extends BaseRepository
{
    public function __construct(protected TenantUserEmail $tenantUserEmailModel)
    {
        parent::__construct($tenantUserEmailModel);
    }
}