<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Loan
 * 
 * @property int $id
 * @property float $amount
 * @property float $interest_rate
 * @property string $interest_type
 * @property string $payment_frequency
 * @property int $installments_count
 * @property Carbon $start_date
 * @property float $outstanding_balance
 * @property string $status
 * @property int $client_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Client $client
 * @property User $user
 * @property Collection|Installment[] $installments
 * @property Collection|Payment[] $payments
 *
 * @package App\Models
 */
class Loan extends Model
{
	protected $table = 'loans';

	protected $casts = [
		'amount' => 'float',
		'interest_rate' => 'float',
		'installments_count' => 'int',
		'start_date' => 'datetime',
		'outstanding_balance' => 'float',
		'client_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
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
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function installments()
	{
		return $this->hasMany(Installment::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}
}
