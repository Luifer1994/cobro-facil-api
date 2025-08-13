<?php

namespace App\Http\Modules\Tenants\Models;

use App\Http\Bases\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class TenantUserEmail extends BaseModel
{
    use HasFactory;
    protected $table = 'tenant_user_emails';
    protected $connection = 'central';

    protected $fillable = [
        'tenant_id',
        'email',
        'is_active'
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'id', 'tenant_id');
    }
}
