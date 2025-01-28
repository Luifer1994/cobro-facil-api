<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientLocation
 * 
 * @property int $id
 * @property float $latitude
 * @property float $longitude
 * @property int $client_id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Client $client
 * @property User $user
 *
 * @package App\Models
 */
class ClientLocation extends Model
{
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
