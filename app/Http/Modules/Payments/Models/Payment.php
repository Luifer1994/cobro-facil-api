<?php

namespace App\Http\Modules\Payments\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Bases\BaseModel;
use App\Http\Modules\Installments\Models\Installment;
use App\Http\Modules\Loans\Models\Loan;
use App\Http\Modules\Users\Models\User;

class Payment extends BaseModel
{
    use HasFactory;

    protected $table = 'payments';

	protected $casts = [
		'amount' => 'float',
		'loan_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'amount',
		'payment_type',
		'allocated_to',
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

	public function installments()
	{
		return $this->belongsToMany(Installment::class, 'installment_payments')
					->withPivot('id', 'allocated_amount', 'user_id')
					->withTimestamps();
	}
}