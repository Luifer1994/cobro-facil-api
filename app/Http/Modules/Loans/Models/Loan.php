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