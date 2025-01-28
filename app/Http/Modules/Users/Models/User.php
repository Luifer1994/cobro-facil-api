<?php

namespace App\Http\Modules\Users\Models;

use App\Http\Modules\DocumentTypes\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    protected $table = 'users';

    protected $guard_name = 'api';

    protected $casts = [
        'is_active' => 'bool'
    ];

    protected $dates = [
        'email_verified_at',
        'changed_at'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'name',
        'email',
        'is_active',
        'email_verified_at',
        'password',
        'remember_token',
        'changed_at'
    ];
}
