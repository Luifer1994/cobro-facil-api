<?php

namespace App\Http\Modules\Plans\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Plans\Models\PlanTenant;

class PlanTenantRepository extends BaseRepository
{
    /**
     * PlanTenantRepository constructor.
     *
     * @param PlanTenant $planTenantModel
     */
    public function __construct(protected PlanTenant $planTenantModel)
    {
        parent::__construct($planTenantModel);
    }

    /**
     * Get plan tenant by tenant id and plan id and is active.
     *
     * @param string $tenantId
     * @param int $planId
     * @return object|null
     * @author Luifer Almendrales, Brandon Torres
     */
    public function getPlanTenantByTenantIdAndPlanIdAndIsActive(string $tenantId, int $planId): ?object
    {
        return $this->planTenantModel->where('tenant_id', $tenantId)
            ->where('plan_id', $planId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Deactivate the current plan of the tenant.
     *
     * @param string $tenantId
     * @return bool
     * @author Luifer Almendrales, Brandon Torres
     */
    public function deactivateCurrentPlan(string $tenantId): bool
    {
        return $this->planTenantModel->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }

    /**
     * Get the last end date of the tenant plan.
     *
     * @param string $tenantId
     * @param int $planId
     * @return string|null
     * @author Luifer Almendrales, Brandon Torres
     */
    public function getLastEndDate(string $tenantId): ?string
    {
        return $this->planTenantModel->where('tenant_id', $tenantId)
            ->orderBy('end_date', 'desc')
            ->first()
            ->end_date;
    }
}
