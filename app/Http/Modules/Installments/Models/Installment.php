<?php

namespace App\Http\Modules\Installments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Bases\BaseModel;
use App\Http\Modules\Loans\Models\Loan;
use App\Http\Modules\Payments\Models\Payment;
use App\Http\Modules\Users\Models\User;

class Installment extends BaseModel
{
    use HasFactory;

    protected $table = 'installments';

	protected $casts = [
		'due_date' => 'datetime',
		'expected_amount' => 'float',
		'capital_balance' => 'float',
		'interest_balance' => 'float',
		'is_paid' => 'bool',
		'loan_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'due_date',
		'expected_amount',
		'capital_balance',
		'interest_balance',
		'is_paid',
		'loan_id',
		'user_id'
	];

	public function loan()
	{
		return $this->belongsTo(Loan::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function payments()
	{
		return $this->belongsToMany(Payment::class, 'installment_payments')
					->withPivot('id', 'allocated_amount', 'user_id')
					->withTimestamps();
	}
}