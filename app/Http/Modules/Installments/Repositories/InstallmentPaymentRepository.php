<?php

namespace App\Http\Modules\Installments\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Installments\Models\InstallmentPayment;

class InstallmentPaymentRepository extends BaseRepository
{
    /**
     * InstallmentPaymentRepository constructor.
     *
     * @param  InstallmentPayment $installmentPayment
     */
    public function __construct(protected InstallmentPayment $installmentPayment)
    {
        parent::__construct($installmentPayment);
    }
}