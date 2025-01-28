<?php

namespace App\Http\Modules\Plans\Repositories;

use App\Http\Bases\BaseRepository;
use App\Http\Modules\Plans\Models\Plan;

class PlanRepository extends BaseRepository
{
    /**
     * PlanRepository constructor.
     *
     * @param Plan $planModel
     */
    public function __construct(protected Plan $planModel)
    {
        parent::__construct($planModel);
    }

    /**
     * Get all plans active.
     *
     * @return object
     */
    public function getAllPlansActive(): object
    {
        return $this->planModel->select('id', 'name', 'description', 'price', 'is_active', 'number_of_month')
            ->selectRaw('CONCAT(name, " - $", REPLACE(FORMAT(price, 2), ",", "."), " - ", number_of_month, " meses") as name')
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get all plans.
     *
     * @param int $limit
     * @param string $search
     * @return object
     */
    public function getAllPlans(int $limit, string $search): object
    {
        return $this->planModel->select('id', 'name', 'description', 'price', 'is_active', 'number_of_month')
            ->selectRaw('CONCAT(name, " - $", REPLACE(FORMAT(price, 2), ",", "."), " - ", number_of_month, " meses") as full_name')
            ->where('name', 'like', "%$search%")
            ->orWhere('description', 'like', "%$search%")
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }
}
