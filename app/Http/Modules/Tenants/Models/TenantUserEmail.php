<?php

namespace App\Http\Modules\Tenants\Models;

use App\Http\Bases\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
