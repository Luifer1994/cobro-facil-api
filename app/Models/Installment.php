<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Installment
 * 
 * @property int $id
 * @property Carbon $due_date
 * @property float $expected_amount
 * @property float $capital_balance
 * @property float $interest_balance
 * @property bool $is_paid
 * @property int $loan_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Loan $loan
 * @property User $user
 * @property Collection|Payment[] $payments
 *
 * @package App\Models
 */
class Installment extends Model
{
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
