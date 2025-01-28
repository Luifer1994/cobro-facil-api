<?php

namespace App\Http\Modules\Tenants\Models;

use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Plans\Models\Plan;
use App\Http\Modules\DocumentTypes\Models\DocumentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'document_number',
            'address',
            'cell_phone',
            'email',
            'logo',
            'logo_url',
            'primary_color',
            'secondary_color',
            'is_active',
            'document_type_id',
            'user_created_id',
            'city_id',
            'domains',
            'city',
            'documentType'
        ];
    }

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


    /**
     * Returns the custom columns.
     *
     * @return array
     */
    public function toLimitedArray(): array
    {
        return $this->only($this->getCustomColumns());
    }

    /**
     * Relationship with the document type.
     *
     * @return BelongsTo
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Relationship with the city.
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relationship with the plans.
     *
     * @return BelongsToMany
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'plan_tenants')
            ->withPivot('id', 'start_date', 'end_date', 'price', 'is_active', 'user_id')
            ->withTimestamps();
    }
}
