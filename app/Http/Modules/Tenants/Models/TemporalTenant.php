<?php

namespace App\Http\Modules\Tenants\Models;

use App\Http\Bases\BaseModel;
use App\Http\Modules\Plans\Models\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporalTenant extends BaseModel
{
    use HasFactory;

    protected $table = 'temporal_tenants';

    protected $fillable = [
        'id',
        'name',
        'document_number',
        'address',
        'cell_phone',
        'email',
        'logo',
        'primary_color',
        'secondary_color',
        'is_active',
        'document_type_id',
        'user_created_id',
        'city_id',
        'plan_id',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'logo_url',
    ];

    /**
     * Accessor for the logo url.
     *
     * @return string
     */
    public function getLogoUrlAttribute(): string
    {
        if ($this->logo) {
            return config('app.url') . '/storage/' . $this->logo;
        }
        return '';
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
