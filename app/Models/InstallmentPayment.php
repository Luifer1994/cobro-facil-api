<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InstallmentPayment
 * 
 * @property int $id
 * @property float $allocated_amount
 * @property int $installment_id
 * @property int $payment_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Installment $installment
 * @property Payment $payment
 * @property User $user
 *
 * @package App\Models
 */
class InstallmentPayment extends Model
{
	protected $table = 'installment_payments';

	protected $casts = [
		'allocated_amount' => 'float',
		'installment_id' => 'int',
		'payment_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'allocated_amount',
		'installment_id',
		'payment_id',
		'user_id'
	];

	public function installment()
	{
		return $this->belongsTo(Installment::class);
	}

	public function payment()
	{
		return $this->belongsTo(Payment::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
