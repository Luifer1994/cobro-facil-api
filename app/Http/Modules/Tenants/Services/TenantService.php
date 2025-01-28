<?php

namespace App\Http\Modules\Tenants\Services;

use App\Http\Bases\BasePaginateRequest;
use App\Http\Bases\BaseService;
use App\Http\Modules\Plans\Models\PlanTenant;
use App\Http\Modules\Plans\Repositories\PlanRepository;
use App\Http\Modules\Plans\Repositories\PlanTenantRepository;
use App\Http\Modules\RolesAndPermissions\Repositories\RoleRepository;
use App\Support\Result;
use App\Http\Modules\Tenants\Requests\CreateTenantRequest;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Modules\Tenants\Models\Tenant;
use App\Http\Modules\Tenants\Repositories\TenantRepository;
use App\Http\Modules\Tenants\Requests\RenewTenantPlanRequest;
use App\Http\Modules\Tenants\Requests\UpdateTenantRequest;
use App\Http\Modules\Users\Repositories\UserRepository;
use Carbon\Carbon;
use Database\Seeders\TenantSeeder;

class TenantService extends BaseService
{

    protected ?Tenant $newTenant = null;
    protected ?string $logoPath = null;

    public function __construct(
        protected TenantRepository $tenantRepository,
        protected UserRepository $userRepository,
        protected RoleRepository $roleRepository,
        protected PlanTenantRepository $planTenantRepository,
        protected PlanRepository $planRepository,
    ) {}

    /**
     * Create a new tenant.
     *
     * @param CreateTenantRequest $request
     * @return Result
     */
    public function createTenant(CreateTenantRequest $request): Result
    {
        if ($request->hasFile('logo')) $request->files->set('logo', $request->file('logo'));

        try {
            $userCreatedId = Auth::user()->id;
            $logo = null;
            if ($request->hasFile('logo')) {
                $logo = $this->uploadFile($request->file('logo'), 'tenants/logos');
                $this->logoPath = $logo;
            }

            $this->newTenant = $this->tenantRepository->create(
                array_merge($request->validated(), [
                    'user_created_id' => $userCreatedId,
                    'logo' => $logo
                ])
            );

            $plat = $this->planRepository->find($request->plan_id);

            $this->planTenantRepository->save(new PlanTenant([
                'tenant_id' => $this->newTenant->id,
                'plan_id' => $request->plan_id,
                'start_date' => now(),
                'end_date' => now()->addMonths($plat->number_of_month),
                'price' => $plat->price,
                'is_active' => true,
                'user_id' => $userCreatedId,
            ]));

            $domain = $request->id . '.' . config('app.domain_central_api');
            $domain_front = $request->id . '.' . config('app.domain_central_front');
            $this->newTenant->domains()->create([
                'domain' => $domain,
                'domain_front' => $domain_front
            ]);

            $this->newTenant->run(function () use ($request) {
                Artisan::call('db:seed', ['--class' => TenantSeeder::class]);
                $newUser = $this->userRepository->create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'is_active' => true,
                    'email_verified_at' => now(),
                    'password' => bcrypt($request->document_number),
                ]);
                $role = $this->roleRepository->findByName('admin');
                $newUser->assignRole($role);
            });

            return Result::success('Registro creado con éxito');
        } catch (\Throwable $th) {
            custom_log($th, __CLASS__);
            $this->cleanupPartialTenantCreation();
            return Result::failure('Error al crear el registro', $th->getMessage());
        }
    }

    /**
     * Cleanup partial tenant creation.
     */
    protected function cleanupPartialTenantCreation(): void
    {
        if ($this->newTenant) {
            foreach ($this->newTenant->domains as $domain) {
                /** @var \App\Http\Modules\Tenants\Models\Domain $domain */
                $domain->delete();
            }
            $this->tenantRepository->forceDelete($this->newTenant);
            $this->dropTenantDatabase($this->newTenant);
        }
        if ($this->logoPath) {
            $this->deleteFile($this->logoPath);
        }
    }

    /**
     * Drop tenant database.
     *
     * @param Tenant $tenant
     * @return void
     */
    protected function dropTenantDatabase(Tenant $tenant): void
    {
        $dbName = 'tenant_' . $tenant->id;
        try {
            DB::statement("DROP DATABASE IF EXISTS `$dbName`");
        } catch (Exception $e) {
            Log::error("No se pudo eliminar la base de datos $dbName: " . $e->getMessage());
        }
    }

    /**
     * Get all tenants.
     *
     * @param BasePaginateRequest $request
     * @return Result
     */
    public function getAllTenants(BasePaginateRequest $request): Result
    {
        try {
            $limit  = $request->limit ?? 10;
            $search = $request->search ?? '';

            return Result::success('Tenants obtenidos con éxito', $this->tenantRepository->getAllTenants($limit, $search));
        } catch (\Throwable $th) {
            custom_log($th, __CLASS__, 'getAllTenants');
            return Result::failure('Error al obtener los tenants', $th->getMessage());
        }
    }

     /**
     * Renew Tenant plan.
     *
     * @param RenewTenantPlanRequest $request
     * @return Result
     */
    public function renewTenantPlan(RenewTenantPlanRequest $request): Result
    {
        DB::beginTransaction();

        try {
            $tenant = $this->tenantRepository->getTenantById($request->tenant_id);
            $plan = $this->planRepository->find($request->plan_id);

            if (!$tenant || !$plan) {
                DB::rollBack();
                return Result::failure('Error al renovar el plan', "El plan $request->plan_id no existe");
            }

            // Deactivate the current plan
            $this->planTenantRepository->deactivateCurrentPlan($tenant->id);

            // Calculate the end date of the new plan
            $endDateLastPlan = $this->planTenantRepository->getLastEndDate($tenant->id);
            $endDateLastPlan = $endDateLastPlan ? Carbon::parse($endDateLastPlan) : now();
            $enDateNewPlan = now()->gt($endDateLastPlan)
                ? now()->addMonths($plan->number_of_month)
                : $endDateLastPlan->addMonths($plan->number_of_month);

            // Active the new plan
            $this->planTenantRepository->save(new PlanTenant([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'start_date' => now(),
                'end_date' => $enDateNewPlan,
                'price' => $plan->price,
                'is_active' => true,
                'user_id' => Auth::user()->id,
            ]));

            if (!$tenant->is_active) {
                $this->tenantRepository->save($tenant->fill(['is_active' => true]));
            }

            DB::commit();
            return Result::success('Plan renovado con éxito');
        } catch (\Throwable $th) {
            DB::rollBack();
            return Result::failure('Error al renovar el plan', $th->getMessage());
        }
    }

    /**
     * Update Tenant.
     *
     * @param updateTenant $request
     * @param string $id
     * @return Result
     */
    public function updateTenant(UpdateTenantRequest $request, string $id): Result
    {
        try {
            $tenant = $this->tenantRepository->getTenantById($id);
            if (!$tenant)
                return Result::failure('Error al actualizar el tenant', "El tenant $id no existe");

            $updateData = $request->all();

            if ($request->hasFile('logo')) {
                // yes exist logo, delete the old logo
                if ($tenant->logo) {
                    $this->deleteFile($tenant->logo);
                }

                // upload new logo
                $logo = $this->uploadFile($request->file('logo'), 'tenants/logos');
                $updateData['logo'] = $logo;
            }

            $TenantUpdate = $this->tenantRepository->save($tenant->fill($updateData));

            return Result::success('Tenant actualizado con éxito', $TenantUpdate);
        } catch (\Throwable $th) {
            return Result::failure('Error al actualizar el tenant', $th->getMessage());
        }
    }

    /**
     * Get Tenant by id.
     *
     * @param string $id
     * @return Result
     */
    public function getTenantById(string $id): Result
    {
        try {
            $tenant = $this->tenantRepository->getTenantById($id);
            if (!$tenant)
                return Result::failure('Error al obtener el tenant', "El tenant $id no existe");

            //convert logo to url in base64 format
            $tenant->logo = $this->getFile($tenant->logo);
            $tenant->plan_id = $tenant->plans->first()->id;
            $tenant->current_plan = $tenant->plans->first();

            return Result::success('Tenant obtenido con éxito', $tenant);
        } catch (\Throwable $th) {
           custom_log($th, __CLASS__);
            return Result::failure('Error al obtener el tenant '. $th->getMessage(),
            $th->getMessage());
        }
    }

    /**
     * Validate Tenant by id.
     *
     * @param string $id
     * @return Result
     */
    public function validateActiveTenant(string $id): Result
    {
        try {
            $tenant = $this->tenantRepository->getTenantActiveById($id);
            if (!$tenant)
                return Result::failure('Error al validar el tenant', "El tenant $id no existe");

            if ($tenant->logo) {
                $tenant->logo =  $this->getFile($tenant->logo);
            }

            return Result::success('Tenant validado con éxito', $tenant);
        } catch (\Throwable $th) {
            return Result::failure('Error al validar el tenant', $th->getMessage());
        }
    }
}
