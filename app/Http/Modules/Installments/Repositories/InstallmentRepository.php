<?php

namespace App\Http\Modules\Installments\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Installments\Models\Installment;

class InstallmentRepository extends BaseRepository
{
    /**
     * InstallmentRepository constructor.
     *
     * @param  Installment $installment
     */
    public function __construct(protected Installment $installment)
    {
        parent::__construct($installment);
    }
}