<?php

namespace App\Http\Modules\Loans\Services;

use App\Http\Modules\Loans\Repositories\LoanRepository;
USE App\Http\Bases\BaseService;

class LoanService extends BaseService
{
    /**
     * LoanService constructor.
     *
     * @param  LoanRepository $loanRepository
     */
    public function __construct(protected LoanRepository $loanRepository)
    {
        //
    }

    // Aquí puedes añadir métodos propios de la capa de servicio
}