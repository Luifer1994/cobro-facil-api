<?php

namespace App\Http\Modules\Loans\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\Loans\Repositories\LoanRepository;
use App\Http\Modules\Loans\Services\LoanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LoanController extends BaseController
{
    /**
     * LoanController constructor.
     *
     * @param  LoanRepository $loanRepository
     * @param  LoanService $loanService
     */
    public function __construct(
        protected LoanRepository $loanRepository,
        protected LoanService $loanService
    ) {
        //
    }

    // Aquí puedes añadir tus métodos (index, show, store, update, destroy, etc.)
}