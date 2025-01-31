<?php

namespace App\Http\Modules\Loans\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Bases\BaseModel;
use App\Http\Modules\Clients\Models\Client;
use App\Http\Modules\Installments\Models\Installment;
use App\Http\Modules\Payments\Models\Payment;
use App\Http\Modules\Users\Models\User;

class Loan extends BaseModel
{
	use HasFactory;

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
		'user_id',
		'description'
	];

	/**
	 * The attributes that should be appended to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = [
		'status_es',
		'color_status',
		'interest_type_es',
		'color_interest_type',
		'payment_frequency_es',
		'color_payment_frequency',
	];

	/**
	 * Accessor for the status_es attribute.
	 *
	 * @return string
	 */
	public function getStatusEsAttribute(): string
	{
		return match ($this->status) {
			'active' => 'Activo',
			'finished' => 'Finalizado',
			'defaulted' => 'En Mora'
		};
	}

	/**
	 * Accessor for the interest_type_es attribute.
	 *
	 * @return string
	 */
	public function getInterestTypeEsAttribute(): string
	{
		return match ($this->interest_type) {
			'fixed' => 'Fijo',
			'reducing' => 'Reduciendo'
		};
	}

	/**
	 * Accessor for the payment_frequency_es attribute.
	 *
	 * @return string
	 */
	public function getPaymentFrequencyEsAttribute(): string
	{
		return match ($this->payment_frequency) {
			'daily' => 'Diaria',
			'weekly' => 'Semanal',
			'biweekly' => 'Quincenal',
			'monthly' => 'Mensual'
		};
	}

	public function getColorStatusAttribute(): string
	{
		return match ($this->status) {
			'active' => 'primary',
			'finished' => 'success',
			'defaulted' => 'error'
		};
	}

	public function getColorInterestTypeAttribute(): string
	{
		return match ($this->interest_type) {
			'fixed' => 'info',
			'reducing' => 'error'
		};
	}

	public function getColorPaymentFrequencyAttribute(): string
	{
		return match ($this->payment_frequency) {
			'daily' => 'primary',
			'weekly' => 'success',
			'biweekly' => 'warning',
			'monthly' => 'error'
		};
	}

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
