<?php

namespace App\Http\Modules\Tenants\Services;

use App\Http\Bases\BaseService;
use App\Http\Modules\Tenants\Repositories\TemporalTenantRepository;
use App\Http\Modules\Tenants\Requests\CreateTenantRequest;
use App\Support\Result;

class CreateTemporalTenantService extends BaseService
{

    protected ?string $logoPath = null;
    /**
     * CreateTemporalTenantService constructor.
     *
     * @param  TemporalTenantRepository $temporalTenantRepository
     */
    public function __construct(protected TemporalTenantRepository $temporalTenantRepository) {}

    public function execute(CreateTenantRequest $request): Result
    { {
            if ($request->hasFile('logo')) $request->files->set('logo', $request->file('logo'));

            try {
                $userCreatedId = 1;
                $logo = null;
                if ($request->hasFile('logo')) {
                    $logo = $this->uploadFile($request->file('logo'), 'tenants/logos');
                    $this->logoPath = $logo;
                }

                $this->temporalTenantRepository->create(
                    array_merge($request->validated(), [
                        'user_created_id' => $userCreatedId,
                        'logo' => $logo,
                        'is_approved' => false
                    ])
                );
                return Result::success('Registro creado con éxito');
            } catch (\Throwable $th) {
                custom_log($th, __CLASS__);

                return Result::failure('Error al crear el registro', $th->getMessage());
            }
        }
    }
}
