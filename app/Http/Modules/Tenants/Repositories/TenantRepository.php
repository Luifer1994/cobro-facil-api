<?php

namespace App\Http\Modules\Tenants\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Tenants\Models\Tenant;

class TenantRepository extends BaseRepository
{
    public function __construct(protected Tenant $tenantModel)
    {
        parent::__construct($tenantModel);
    }

    /**
     * Create a new tenant.
     *
     * @param  array  $data
     * @return Tenant
     */
    public function create(array $data): Tenant
    {
        return $this->tenantModel->create($data);
    }

    /**
     * Get all Tenants.
     *
     * @param int $limit
     * @param string $search
     * @return Object
     * @author Luifer Almendrales, Brandon Torres
     */
    public function getAllTenants(int $limit, string $search): object
    {
        return $this->tenantModel
            ->select('id', 'name', 'document_number', 'email', 'is_active', 'created_at', 'document_type_id', 'logo')
            ->when($search, function ($filter) use ($search) {
                $filter->where('tenants.name', 'like', '%' . $search . '%')
                    ->orWhere('tenants.document_number', 'like', '%' . $search . '%')
                    ->orWhere('tenants.email', 'like', '%' . $search . '%');
            })
            ->with([
                'documentType:id,name,code',
                'domains',
                'city:id,name',
                'plans' => function ($query) {
                    $query->select('plans.id', 'plans.name', 'plans.price', 'plans.number_of_month')
                        ->selectRaw('DATEDIFF(plan_tenants.end_date, CURDATE()) as remaining_days')
                        ->selectRaw('plan_tenants.start_date as start_date')
                        ->selectRaw('plan_tenants.end_date as end_date')
                        ->selectRaw('CONCAT(plans.name, " - $", REPLACE(FORMAT(plan_tenants.price, 2), ",", ".")) as plan_name')
                        ->selectRaw("
                    CASE
                        WHEN plan_tenants.is_active = 0 OR plan_tenants.end_date < CURDATE() THEN 'Vencido'
                        WHEN DATEDIFF(plan_tenants.end_date, CURDATE()) <= 15 THEN 'Próximo a vencer'
                        ELSE 'Activo'
                    END as status
                ")
                        ->selectRaw("
                    CASE
                        WHEN plan_tenants.is_active = 0 OR plan_tenants.end_date < CURDATE() THEN '#FC4B6C'  -- Rojo
                        WHEN DATEDIFF(plan_tenants.end_date, CURDATE()) <= 15 THEN '#FEC90F'  -- Amarillo Naranja
                        ELSE '#05B187'  -- Verde
                    END as status_color
                ")
                        ->orderBy('plan_tenants.is_active', 'desc')
                        ->orderBy('plan_tenants.created_at', 'desc');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get Tenant by id.
     *
     * @param string $id
     * @return object
     * @author Luifer Almendrales, Brandon Torres
     */
    public function getTenantById(string $id): ?object
    {
        return $this->tenantModel
            ->select(
                'id',
                'name',
                'document_number',
                'address',
                'cell_phone',
                'email',
                'logo',
                'primary_color',
                'secondary_color',
                'is_active',
                'document_type_id',
                'user_created_id',
                'city_id'
            )

            ->with([
                'documentType:id,name,code',
                'domains',
                'city:id,name',
                'plans' => function ($query) {
                    $query->select('plans.id', 'plans.name', 'plans.price', 'plans.number_of_month')

                        ->selectRaw('DATEDIFF(plan_tenants.end_date, CURDATE()) as remaining_days')
                        ->selectRaw('plan_tenants.start_date as start_date')
                        ->selectRaw('plan_tenants.end_date as end_date')
                        ->selectRaw('CONCAT(plans.name, " - $", REPLACE(FORMAT(plan_tenants.price, 2), ",", ".")) as plan_name')
                        ->selectRaw("
                    CASE
                        WHEN plan_tenants.is_active = 0 OR plan_tenants.end_date < CURDATE() THEN 'Vencido'
                        WHEN DATEDIFF(plan_tenants.end_date, CURDATE()) <= 15 THEN 'Próximo a vencer'
                        ELSE 'Activo'
                    END as status
                ")
                        ->selectRaw("
                    CASE
                        WHEN plan_tenants.is_active = 0 OR plan_tenants.end_date < CURDATE() THEN '#FC4B6C'  -- Rojo
                        WHEN DATEDIFF(plan_tenants.end_date, CURDATE()) <= 15 THEN '#FEC90F'  -- Amarillo Naranja
                        ELSE '#05B187'  -- Verde
                    END as status_color
                ")
                        ->orderBy('plan_tenants.is_active', 'desc')
                        ->orderBy('plan_tenants.created_at', 'desc');
                }
            ])
            ->find($id);
    }

    /**
     * Get Tenant by id active and with plans.
     *
     * @param string $id
     * @return object
     * @author Luifer Almendrales, Brandon Torres
     */
    public function getTenantActiveById(string $id): ?object
    {
        return $this->tenantModel
            ->select('id', 'name', 'email', 'is_active', 'logo', 'primary_color', 'secondary_color')
            ->with([
                'domains:id,tenant_id,domain,domain_front',
                'plans' => function ($query) {
                    $query->select('plans.id', 'plans.name', 'plans.price', 'plans.number_of_month')
                        ->where('plan_tenants.is_active', 1)
                        ->where('plan_tenants.end_date', '>', now());
                }
            ])
            ->where('is_active', 1)
            ->whereHas('plans', function ($query) {
                $query->where('plan_tenants.is_active', 1)
                    ->where('plan_tenants.end_date', '>', now());
            })
            ->find($id);
    }
}
