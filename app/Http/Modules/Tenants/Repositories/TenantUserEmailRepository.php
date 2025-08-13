<?php

namespace App\Http\Modules\Tenants\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Tenants\Models\Tenant;
use App\Http\Modules\Tenants\Models\TenantUserEmail;
use Illuminate\Database\Eloquent\Collection;

class TenantUserEmailRepository extends BaseRepository
{
    public function __construct(protected TenantUserEmail $tenantUserEmailModel)
    {
        parent::__construct($tenantUserEmailModel);
    }

    public function getByEmail(string $email): Collection
    {
        return $this->tenantUserEmailModel->where('email', $email)
            ->where('is_active', true)
            ->with(['tenants'])
            ->get();
    }
}
