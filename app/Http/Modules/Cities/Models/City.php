<?php

namespace App\Http\Modules\Cities\Models;

use App\Http\Bases\BaseModel;
use App\Http\Modules\Departments\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class City extends BaseModel
{
    use HasFactory;
    protected $table = 'cities';

    protected $casts = [
        'department_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'code',
        'department_id'
    ];

    /**
     * Relationship with department
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
