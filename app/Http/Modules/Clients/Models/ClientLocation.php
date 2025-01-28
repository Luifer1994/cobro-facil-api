<?php

namespace App\Http\Modules\Clients\Models;

use App\Http\Bases\BaseModel;
use App\Http\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientLocation extends BaseModel
{
    use HasFactory;

    protected $table = 'client_locations';

	protected $casts = [
		'latitude' => 'float',
		'longitude' => 'float',
		'client_id' => 'int',
		'user_id' => 'int'
	];

	protected $fillable = [
		'latitude',
		'longitude',
		'client_id',
		'user_id'
	];

	public function client()
	{
		return $this->belongsTo(Client::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}