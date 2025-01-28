<?php

namespace App\Http\Modules\Payments\Controllers;

use App\Http\Bases\BaseController;
use App\Http\Modules\Payments\Repositories\PaymentRepository;
use App\Http\Modules\Payments\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PaymentController extends BaseController
{
    /**
     * PaymentController constructor.
     *
     * @param  PaymentRepository $paymentRepository
     * @param  PaymentService $paymentService
     */
    public function __construct(
        protected PaymentRepository $paymentRepository,
        protected PaymentService $paymentService
    ) {
        //
    }

    // Aquí puedes añadir tus métodos (index, show, store, update, destroy, etc.)
}