<?php

namespace App\Http\Modules\RolesAndPermissions\Repositories;

use App\Http\Bases\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository
{
    function __construct(protected Role $modelRole) {}

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
}
