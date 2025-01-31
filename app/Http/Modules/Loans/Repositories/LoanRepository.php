<?php

namespace App\Http\Modules\Loans\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Loans\Models\Loan;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanRepository extends BaseRepository
{
    public function __construct(protected Loan $loanModel)
    {
        parent::__construct($loanModel);
    }

    /**
     * Get all loans.
     * 
     * @param int $limit
     * @param string $search
     * @return LengthAwarePaginator
     */
    public function index(int $limit, string $search): LengthAwarePaginator
    {
        return $this->loanModel
            ->select(
                'id',
                'amount',
                'interest_rate',
                'interest_type',
                'payment_frequency',
                'installments_count',
                'start_date',
                'outstanding_balance',
                'status',
                'client_id',
                'user_id'
            )
            ->with([
                'client' => fn($query) => $query->select('id', 'name', 'email', 'phone', 'address', 'document', 'document_type_id')
                ->with('document_type:id,name,code'),
                'user:id,name,email,phone',
                'installments' => fn($query) => $query->where('is_paid', false)->where('due_date', '>', Carbon::now())
                    ->orderBy('due_date', 'asc')->limit(1)->select('loan_id', 'due_date', 'expected_amount')
            ])
            ->withCount([
                'installments as installments_total_count',
                'installments as installments_paid_count' => fn($query) => $query->where('is_paid', true),
                'installments as installments_due_count' => fn($query) => $query->where('is_paid', false)->where('due_date', '<', Carbon::now()),
                'installments as installments_pending_count' => fn($query) => $query->where('is_paid', false)->where('due_date', '>', Carbon::now())
            ])
            ->withSum(['installments as total_paid' => fn($query) => $query->where('is_paid', true)], 'expected_amount')
            ->withSum(['installments as total_overdue' => fn($query) => $query->where('is_paid', false)->where('due_date', '<', Carbon::now())], 'expected_amount')
            ->withMax('installments as estimated_end_date', 'due_date')
            ->when(!Auth::user()->can('loans-list-all'), fn($query) => $query->where('user_id', Auth::id()))
            ->when(
                $search,
                fn($query) => $query->whereHas(
                    'client',
                    fn($query) => $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('document', 'like', '%' . $search . '%')
                )
            )
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }
}
