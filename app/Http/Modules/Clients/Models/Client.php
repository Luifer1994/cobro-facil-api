<?php

namespace App\Http\Modules\Clients\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Bases\BaseModel;
use App\Http\Modules\DocumentTypes\Models\DocumentType;
use App\Http\Modules\Loans\Models\Loan;
use App\Http\Modules\Users\Models\User;

class Client extends BaseModel
{
    use HasFactory;

    protected $table = 'clients';

    protected $casts = [
        'user_id' => 'int',
        'document_type_id' => 'int'
    ];

    protected $fillable = [
        'document',
        'name',
        'phone',
        'email',
        'address',
        'user_id',
        'document_type_id'
    ];

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client_locations()
    {
        return $this->hasMany(ClientLocation::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
