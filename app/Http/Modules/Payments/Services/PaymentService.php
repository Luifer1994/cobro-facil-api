<?php

namespace App\Http\Modules\Payments\Services;

use App\Http\Modules\Payments\Repositories\PaymentRepository;
USE App\Http\Bases\BaseService;

class PaymentService extends BaseService
{
    /**
     * PaymentService constructor.
     *
     * @param  PaymentRepository $paymentRepository
     */
    public function __construct(protected PaymentRepository $paymentRepository)
    {
        //
    }

    // Aquí puedes añadir métodos propios de la capa de servicio
}