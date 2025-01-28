<?php

namespace App\Http\Modules\Installments\Models;

use App\Http\Bases\BaseModel;
use App\Http\Modules\Payments\Models\Payment;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstallmentPayment extends BaseModel
{
    use HasFactory;

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