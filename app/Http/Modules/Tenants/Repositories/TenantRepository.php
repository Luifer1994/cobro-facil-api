<?php

namespace App\Http\Modules\Tenants\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Tenants\Models\Tenant;
use Illuminate\Support\Facades\DB;

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
        // Query para tenants temporales (vienen primero)
        $temporalTenants = DB::table('temporal_tenants')
            ->select(
                'id',
                'name',
                'document_number',
                'email',
                'is_active',
                'created_at',
                'document_type_id',
                'logo'
            )
            ->selectRaw("CONCAT('" . config('app.url') . "/storage/', logo) as logo_url")
            ->selectRaw("'temporal' as source_table")
            ->selectRaw('false as is_approved')
            ->selectRaw('NULL as plan_id') // NULL para temporales también
            ->selectRaw('0 as priority') // Prioridad alta para temporales
            ->when($search, function ($filter) use ($search) {
                $filter->where('temporal_tenants.name', 'like', '%' . $search . '%')
                    ->orWhere('temporal_tenants.document_number', 'like', '%' . $search . '%')
                    ->orWhere('temporal_tenants.email', 'like', '%' . $search . '%');
            });

        // Query para tenants reales (vienen después)
        $realTenants = $this->tenantModel
            ->select(
                'id',
                'name',
                'document_number',
                'email',
                'is_active',
                'created_at',
                'document_type_id',
                'logo'
            )
            ->selectRaw("CONCAT('" . config('app.url') . "/storage/', logo) as logo_url")
            ->selectRaw("'tenant' as source_table")
            ->selectRaw('true as is_approved')
            ->selectRaw('NULL as plan_id') // NULL para reales también
            ->selectRaw('1 as priority') // Prioridad baja para reales
            ->when($search, function ($filter) use ($search) {
                $filter->where('tenants.name', 'like', '%' . $search . '%')
                    ->orWhere('tenants.document_number', 'like', '%' . $search . '%')
                    ->orWhere('tenants.email', 'like', '%' . $search . '%');
            });

        // Unir ambas consultas y ordenar por prioridad y fecha
        $combinedQuery = $temporalTenants->union($realTenants)
            ->orderBy('priority', 'asc') // Temporales primero (priority 0)
            ->orderBy('created_at', 'desc'); // Luego por fecha

        // Paginar el resultado combinado
        $paginatedResults = $this->paginateUnionQuery($combinedQuery, $limit);

        // Ahora agregar las relaciones para cada tipo de tenant
        $items = $paginatedResults->getCollection();
        $enhancedItems = [];

        foreach ($items as $item) {
            if ($item->source_table === 'temporal') {
                // Para temporales: traer plan directo y relaciones básicas
                $enhancedItem = $this->enhanceTemporalTenant($item);
            } else {
                // Para reales: traer todas las relaciones completas
                $enhancedItem = $this->enhanceRealTenant($item);
            }
            $enhancedItems[] = $enhancedItem;
        }

        // Reemplazar la colección con los items mejorados
        $paginatedResults->setCollection(collect($enhancedItems));
        
        return $paginatedResults;
    }

    /**
     * Método auxiliar para paginar consultas UNION
     */
    private function paginateUnionQuery($query, $limit)
    {
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $limit;
        
        // Obtener total de registros
        $total = DB::query()->fromSub($query, 'combined_tenants')->count();
        
        // Obtener registros paginados
        $items = DB::query()->fromSub($query, 'combined_tenants')
            ->offset($offset)
            ->limit($limit)
            ->get();

        // Crear paginador personalizado
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $limit,
            $page,
            ['path' => request()->url(), 'pageName' => 'page']
        );
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

    /**
     * Mejorar tenant temporal con sus relaciones
     */
    private function enhanceTemporalTenant($item)
    {
        // Traer el plan asociado directamente
        $plan = DB::table('plans')
            ->select('id', 'name', 'price', 'number_of_month')
            ->where('id', $item->plan_id)
            ->first();

        // Traer document type - usar document_type (con guión bajo) para consistencia
        $documentType = DB::table('document_types')
            ->select('id', 'name', 'code')
            ->where('id', $item->document_type_id)
            ->first();

        // Traer city (corregir el campo city_id)
        $city = DB::table('cities')
            ->select('id', 'name')
            ->where('id', $item->city_id ?? 1) // Usar city_id si existe, sino default
            ->first();

        // Agregar las relaciones al item - usar document_type para consistencia
        $item->plan = $plan;
        $item->document_type = $documentType; // Cambiar a document_type
        $item->city = $city;
        $item->domains = collect(); // Temporales no tienen domains
        $item->plans = collect([$plan])->filter(); // Solo el plan asociado

        // Asegurar que logo_url esté disponible
        if (!isset($item->logo_url) && isset($item->logo)) {
            $item->logo_url = config('app.url') . '/storage/' . $item->logo;
        }

        return $item;
    }

    /**
     * Mejorar tenant real con todas sus relaciones
     */
    private function enhanceRealTenant($item)
    {
        // Usar el modelo Tenant para traer todas las relaciones
        $realTenant = $this->tenantModel
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
                                WHEN plan_tenants.is_active = 0 OR plan_tenants.end_date < CURDATE() THEN '#FC4B6C'
                                WHEN DATEDIFF(plan_tenants.end_date, CURDATE()) <= 15 THEN '#FEC90F'
                                ELSE '#05B187'
                            END as status_color
                        ")
                        ->orderBy('plan_tenants.is_active', 'desc')
                        ->orderBy('plan_tenants.created_at', 'desc');
                }
            ])
            ->find($item->id);

        // Agregar los campos adicionales
        $realTenant->source_table = 'tenant';
        $realTenant->is_approved = true;
        $realTenant->plan_id = null;
        $realTenant->priority = 1;

        // Asegurar que document_type esté disponible (convertir documentType a document_type)
        if (isset($realTenant->documentType)) {
            $realTenant->document_type = $realTenant->documentType;
        }

        // Asegurar que logo_url esté disponible
        if (!isset($realTenant->logo_url) && isset($realTenant->logo)) {
            $realTenant->logo_url = config('app.url') . '/storage/' . $realTenant->logo;
        }

        return $realTenant;
    }
}
