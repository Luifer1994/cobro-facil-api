<?php

namespace App\Http\Modules\Cities\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Cities\Models\City;
use Illuminate\Database\Eloquent\Collection;

class CityRepository extends BaseRepository
{
    public function __construct(protected City $cityModel)
    {
        parent::__construct($cityModel);
    }

    /**
     * List all cities.
     * 
     * @param int $departmentId
     * @return Collection
     */
    public function listByDepartment(int $departmentId): Collection
    {
        return $this->cityModel->select('id', 'name', 'code', 'department_id')
            ->with(['department' => function ($query) {
                $query->select('id', 'name', 'code', 'country_id')
                    ->with(['country:id,name,iso_code,iso_code3']);
            }])
            ->where('department_id', $departmentId)
            ->get();
    }

    /**
     * Get all cities with deparments.
     *
     * @param  int $limit
     * @param  string $search
     * @return object
     */
    public function getCities($limit, $search): object
    {
        return $this->cityModel
            ->select('id', 'name', 'department_id')
            ->with(['department' => function ($query) {
                $query->select('id', 'name', 'country_id')
                    ->with(['country:id,name']);
            }])
            ->where('name', 'like', "%$search%")
            ->paginate($limit);
    }

    /**
     * Get all cities.
     *
     * @param  int $limit
     * @param  string $search
     * @return object
     */
    public function getAllCities(/* $limit,  */$search): object
    {
        return $this->cityModel
            ->select('id', 'name', 'department_id')
            ->where('name', 'like', "%$search%")
            ->with(['department' => function ($query) {
                $query->select('id', 'name', 'country_id')
                    ->with(['country:id,name']);
            }])
            /* ->limit($limit) */
            ->get();
    }
}
