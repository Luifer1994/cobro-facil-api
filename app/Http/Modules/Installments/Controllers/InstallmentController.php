<?php

namespace App\Http\Modules\Installments\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\Installments\Repositories\InstallmentRepository;
use App\Http\Modules\Installments\Services\InstallmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class InstallmentController extends BaseController
{
    /**
     * InstallmentController constructor.
     *
     * @param  InstallmentRepository $installmentRepository
     * @param  InstallmentService $installmentService
     */
    public function __construct(
        protected InstallmentRepository $installmentRepository,
        protected InstallmentService $installmentService
    ) {
        //
    }

    // Aquí puedes añadir tus métodos (index, show, store, update, destroy, etc.)
}