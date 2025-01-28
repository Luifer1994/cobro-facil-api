<?php

namespace App\Http\Modules\Plans\Models;

use App\Http\Bases\BaseModel;
use App\Http\Modules\Tenants\Models\Tenant;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Plan extends BaseModel
{
    protected $table = 'plans';

    protected $casts = [
        'price' => 'float',
        'number_of_month' => 'int',
        'is_active' => 'bool',
        'user_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'number_of_month',
        'is_active',
        'user_id'
    ];

    /**
     * Get the user that owns the Plan
     *
     * @return BelongsTo
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The tenants that belong to the Plan
     *
     * @return BelongsToMany
     */
    public function Tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'plan_tenants')
            ->withPivot('id', 'start_date', 'end_date', 'price', 'is_active', 'user_id')
            ->withTimestamps();
    }
}
