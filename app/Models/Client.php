<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Client
 * 
 * @property int $id
 * @property string $document
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property int $user_id
 * @property int $document_type_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property DocumentType $document_type
 * @property User $user
 * @property Collection|ClientLocation[] $client_locations
 * @property Collection|Loan[] $loans
 *
 * @package App\Models
 */
class Client extends Model
{
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
