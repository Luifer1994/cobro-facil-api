<?php

namespace App\Http\Modules\Auth\Services;

use App\Http\Bases\BaseService;
use App\Http\Modules\Auth\Requests\ValidateEmailForLoginRequest;
use App\Http\Modules\Tenants\Repositories\TenantUserEmailRepository;
use App\Support\Result;

class ValidateEmialForLoginService extends BaseService
{
    public function __construct(private TenantUserEmailRepository $tenantUserEmailRepository)
    {
    }

    public function execute(ValidateEmailForLoginRequest $request): Result
    {
        $tenantUserEmails = $this->tenantUserEmailRepository->getByEmail($request->email);
        if ($tenantUserEmails->isEmpty()) return Result::failure(error: 'El correo electrónico no existe');
        
        return Result::success(value: $tenantUserEmails);
    }
}