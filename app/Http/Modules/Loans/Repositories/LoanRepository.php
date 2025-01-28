<?php

namespace App\Http\Modules\Loans\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Loans\Models\Loan;

class LoanRepository extends BaseRepository
{
    /**
     * LoanRepository constructor.
     *
     * @param  Loan $loan
     */
    public function __construct(protected Loan $loan)
    {
        parent::__construct($loan);
    }
}