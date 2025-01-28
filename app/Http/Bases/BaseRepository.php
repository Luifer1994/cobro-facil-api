<?php

namespace App\Http\Bases;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class BaseRepository
{

    function __construct(
        protected Model $model
    ) {}

    /**
     * Find one row.
     *
     * @param int|str $id
     * @return Model|null
     */
    public function find(int|string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Save and return saved model.
     *
     * @param Model $model
     * @return Model|null
     */
    public function save(Model $model): ?Model
    {
        $model->save();
        return $model;
    }

    /**
     * Get by where params.
     *
     * @param array $where
     * @return Collection
     */
    public function get(array $where = []): Collection
    {
        $query = $this->model;
        if (!empty($where)) {
            $query->where($where);
        }
        return $query->get();
    }

    /**
     * Create a new model.
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a model.
     *
     * @param int $id
     * @param array $data
     * @return Model
     */
    public function update(int $id, array $data): Model
    {
        $model = $this->model->find($id);
        $model->update($data);
        return $model;
    }

    /**
     * Elimina (soft delete) un modelo.
     *
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Elimina permanentemente un modelo (ignora SoftDeletes).
     *
     * @param Model $model
     * @return bool|null
     */
    public function forceDelete(Model $model): ?bool
    {
        if (method_exists($model, 'forceDelete')) {
            return $model->forceDelete();
        }
        // Si el modelo no usa SoftDeletes, fallback a delete().
        return $model->delete();
    }

    /**
     * Actualiza un modelo por su ID.
     *
     * @param int $id
     * @param array $attributes
     * @return Model
     */
    public function updateFind(int $id, array $attributes): Model
    {
        $model = $this->model->findOrFail($id);
        $model->fill($attributes);
        $model->save();
        return $model;
    }

    /**
     * Consulta el último registro.
     * 
     * @param string $attribute
     * @param string $order 'asc' o 'desc'
     * @return Model|null
     */
    public function findLastOrFirst(string $attribute, string $order): ?Model
    {
        return $this->model->orderBy($attribute, $order)
            ->first();
    }

    /**
     * Crear varios registros.
     *
     * @param array $data
     * @return bool
     */
    public function createMany(array $data): bool
    {
        return $this->model->insert($data);
    }
}
