<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * 
 * @property int $id
 * @property float $amount
 * @property string $payment_type
 * @property string $allocated_to
 * @property int $loan_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Loan $loan
 * @property User $user
 * @property Collection|Installment[] $installments
 *
 * @package App\Models
 */
class Payment extends Model
{
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
