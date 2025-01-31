<?php

namespace App\Http\Modules\Loans\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\Loans\Repositories\LoanRepository;
use App\Http\Modules\Loans\Requests\CreateLoanRequest;
use App\Http\Modules\Loans\Services\LoanService;
use App\Support\Result;
use Illuminate\Http\JsonResponse;

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

    /**
     * Create a new loan.
     * 
     * @param  CreateLoanRequest $request
     * @return JsonResponse
     */
    public function store(CreateLoanRequest $request): JsonResponse
    {
        try {
            $result = $this->loanService->create($request->validated());
            return $this->response($result);
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al crear el prestamo', message: $th->getMessage()));
        }
    }
}
