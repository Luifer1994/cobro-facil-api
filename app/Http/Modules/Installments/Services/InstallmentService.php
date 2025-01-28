<?php

namespace App\Http\Modules\Installments\Services;

use App\Http\Modules\Installments\Repositories\InstallmentRepository;
USE App\Http\Bases\BaseService;

class InstallmentService extends BaseService
{
    /**
     * InstallmentService constructor.
     *
     * @param  InstallmentRepository $installmentRepository
     */
    public function __construct(protected InstallmentRepository $installmentRepository)
    {
        //
    }

    // Aquí puedes añadir métodos propios de la capa de servicio
}