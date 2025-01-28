<?php

namespace App\Http\Modules\Countries\Models;

use App\Http\Bases\BaseModel;
use App\Http\Modules\Departments\Models\Department;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends BaseModel
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
        'iso_code',
        'iso_code3'
    ];

    /**
     * Relationship with departments
     *
     * @return HasMany
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
