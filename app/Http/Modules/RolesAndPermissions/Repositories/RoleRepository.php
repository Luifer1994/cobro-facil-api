<?php

namespace App\Http\Modules\RolesAndPermissions\Repositories;

use App\Http\Bases\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository
{
    function __construct(protected Role $modelRole) {
        parent::__construct($modelRole);
    }

    /**
     * Function to get a roles by names.
     *
     * @param string $name
     * @return collection
     */
    public function getRolesByNames(string $name): Collection
    {
        return $this->modelRole->select('*')
            ->when(tenancy()->initialized, function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->where('name', $name)
            ->get();
    }

    /**
     * Function created new role.
     *
     * @param array $data
     * @return Role
     */
    public function create(array $data): Role
    {
        return $this->modelRole->create($data);
    }

    /**
     * Find role by name.
     *
     * @param string $name
     * @return Role|null
     */
    public function findByName(string $name): ?Role
    {
        return $this->modelRole->where('name', $name)->first();
    }

     /**
     * Funtion to get all roles.
     *
     * @return Object
     */
    public function getAll(): Object
    {
        return $this->model->select('id', 'name', 'description')
            ->withCount('permissions')
            ->when(tenancy()->initialized, function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->get();
    }

    /**
     * Funtion to get all roles paginated.
     *
     * @param string $search
     * @param int $limit
     * @return Object
     */
    public function getPaginated(string $search, int $limit): Object
    {
        return $this->model->select('id', 'name', 'description')
            ->withCount('permissions')
            ->when(tenancy()->initialized, function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->where('name', 'like', '%' . $search . '%')
            ->paginate($limit);
    }

    /**
     * Funtion to get a role by id.
     *
     * @param int $id
     * @return Object
     */
    public function getById(int $id): Object
    {
        return $this->model->select('id', 'name', 'description', 'guard_name')
            ->when(tenancy()->initialized, function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->findOrFail($id);
    }

    /**
     * Get role by name.
     *
     * @param string $name
     * @return Object
     */
    public function getByName(string $name): Object
    {
        return $this->model->select('id', 'name', 'description', 'guard_name')
            ->when(tenancy()->initialized, function ($query) {
                $query->where('name', '!=', 'admin');
            })
            ->where('name', $name)
            ->first();
    }
}
