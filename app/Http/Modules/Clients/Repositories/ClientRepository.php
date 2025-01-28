<?php

namespace App\Http\Modules\Clients\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Clients\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ClientRepository extends BaseRepository
{
    /**
     * ClientRepository constructor.
     *
     * @param  Client $clientModel
     */
    public function __construct(protected Client $clientModel)
    {
        parent::__construct($clientModel);
    }

    /**
     * Get all clients.
     *
     * @param  int $limit
     * @param  string $search
     * @return LengthAwarePaginator
     */
    public function index(int $limit, string $search): LengthAwarePaginator
    {
        return $this->clientModel->select('id', 'document', 'name', 'phone', 'email', 'address', 'user_id', 'document_type_id')
            ->with(['document_type:id,name,code', 'user:id,name', 'client_locations:id,latitude,longitude,client_id,user_id'])
            ->when($search, function ($filter) use ($search) {
                $filter->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('document', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get Client by id.
     *
     * @param  int $id
     * @return Client|null
     */
    public function findById(int $id): ?Client
    {
        return $this->clientModel->select('id', 'document', 'name', 'phone', 'email', 'address', 'user_id', 'document_type_id')
            ->with(['document_type:id,name,code', 'user:id,name', 'client_locations:id,latitude,longitude,client_id,user_id'])
            ->find($id);
    }

    /**
     * Create a new client.
     *
     * @param  array  $data
     * @return Client
     */
    public function create(array $data): Client
    {
        return $this->clientModel->create($data);
    }
}
