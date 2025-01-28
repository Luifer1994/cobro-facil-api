<?php

namespace App\Http\Modules\Payments\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Payments\Models\Payment;

class PaymentRepository extends BaseRepository
{
    /**
     * PaymentRepository constructor.
     *
     * @param  Payment $payment
     */
    public function __construct(protected Payment $payment)
    {
        parent::__construct($payment);
    }
}