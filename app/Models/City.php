<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * 
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $department_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Department $department
 * @property Collection|Tenant[] $tenants
 *
 * @package App\Models
 */
class City extends Model
{
	protected $table = 'cities';

	protected $casts = [
		'department_id' => 'int'
	];

	protected $fillable = [
		'name',
		'code',
		'department_id'
	];

	public function department()
	{
		return $this->belongsTo(Department::class);
	}

	public function tenants()
	{
		return $this->hasMany(Tenant::class);
	}
}
