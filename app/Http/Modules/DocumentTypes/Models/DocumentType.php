<?php

namespace App\Http\Modules\DocumentTypes\Models;

use App\Http\Bases\BaseModel;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentType extends BaseModel
{
    use HasFactory;
    protected $table = 'document_types';

    protected $dates = [
        'changed_at'
    ];

    protected $fillable = [
        'code',
        'name',
        'changed_at'
    ];

    /**
     * Relationship with users.
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
