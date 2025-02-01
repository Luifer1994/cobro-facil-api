<?php

namespace App\Http\Modules\Loans\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Bases\BasePaginateRequest;
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

    /**
     * List all loans.
     * 
     * @param  BasePaginateRequest
     * @return JsonResponse
     */ 
    public function index(BasePaginateRequest $request): JsonResponse
    {
        $limit = $request->limit ?? 10;
        $search = $request->search ?? '';
        try {
            return $this->response(Result::success(message: 'Listado de prestamos exitoso', value: $this->loanRepository->index($limit, $search)));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener las prestamos '.$th->getMessage(), message: $th->getMessage()));
        }
    }

    /**
     * Show loan.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $loan = $this->loanRepository->findById($id);
            if (!$loan) return $this->response(Result::failure(error: 'Prestamo no encontrado', message: 'Prestamo no encontrado'));
            return $this->response(Result::success('Prestamo obtenido con éxito', $loan));
        } catch (\Throwable $th) {
            return $this->response(Result::failure(error: 'Error al obtener el prestamo', message: $th->getMessage()));
        }
    }
}
