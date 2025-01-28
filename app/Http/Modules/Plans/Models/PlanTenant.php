<?php

namespace App\Http\Modules\Plans\Models;

use App\Http\Modules\Tenants\Models\Tenant;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanTenant extends Model
{
	protected $table = 'plan_tenants';

	protected $casts = [
		'plan_id' => 'int',
		'start_date' => 'datetime',
		'end_date' => 'datetime',
		'price' => 'float',
		'is_active' => 'bool',
		'user_id' => 'int'
	];

	protected $fillable = [
		'plan_id',
		'tenant_id',
		'start_date',
		'end_date',
		'price',
		'is_active',
		'user_id'
	];

    /**
     * Get the plan that owns the PlanTenant
     *
     * @return BelongsTo
     */
	public function Plan(): BelongsTo
	{
		return $this->belongsTo(Plan::class);
	}

    /**
     * Get the tenant that owns the PlanTenant
     *
     * @return BelongsTo
     */
	public function Tenant(): BelongsTo
	{
		return $this->belongsTo(Tenant::class);
	}

    /**
     * Get the user that owns the PlanTenant
     *
     * @return BelongsTo
     */
	public function User(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
