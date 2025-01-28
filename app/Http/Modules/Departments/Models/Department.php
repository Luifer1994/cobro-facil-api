<?php

namespace App\Http\Modules\Departments\Models;

use App\Http\Bases\BaseModel;
use App\Http\Modules\Cities\Models\City;
use App\Http\Modules\Countries\Models\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends BaseModel
{
    use HasFactory;
    protected $table = 'departments';

    protected $casts = [
        'country_id' => 'int'
    ];

    protected $fillable = [
        'id',
        'name',
        'code',
        'country_id'
    ];

    /**
     * Relationship with country
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Relationship with cities
     *
     * @return HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
