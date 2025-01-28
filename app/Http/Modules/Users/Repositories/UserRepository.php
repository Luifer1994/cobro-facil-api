<?php

namespace App\Http\Modules\Users\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Users\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(protected User $userModel)
    {
        parent::__construct($userModel);
    }

    /**
     * Get user by email.
     *
     * @param  string  $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Create a new user.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Get all users
     *
     * @param  int $limit
     * @param  string $search
     * @return Object
     * @author Luifer Almendrales
     */
    public function getAllUsers(int $limit, string $search): Object
    {
        return $this->userModel->select('id', 'name', 'email')
            ->when($search, function ($filter) use ($search) {
                $filter->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get user by id
     *
     * @param  int $id
     * @return Object|null
     */
    public function getById(int $id): ?Object
    {
        return $this->userModel->select('id', 'name', 'email')
            ->with(['roles:id,name,description'])
            ->find($id);
    }
}
