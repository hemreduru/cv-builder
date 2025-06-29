<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class BaseService
{
    /**
     * Model sınıfı
     *
     * @var Model
     */
    protected $model;

    /**
     * CRUD işlemleri için temel fonksiyonları içeren servis sınıfı
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Tüm kayıtları getir
     *
     * @param array $relations İlişkili tablolar (optional)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(array $relations = [])
    {
        $query = $this->model->newQuery();
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }

    /**
     * Kullanıcıya ait tüm kayıtları getir
     *
     * @param int $userId
     * @param array $relations İlişkili tablolar (optional)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllByUser(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }

    /**
     * ID'ye göre kayıt getir
     *
     * @param int $id
     * @param array $relations İlişkili tablolar (optional)
     * @return Model|null
     */
    public function getById(int $id, array $relations = [])
    {
        $query = $this->model->newQuery()->where('id', $id);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->first();
    }

    /**
     * Yeni kayıt oluştur
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Kayıt güncelle
     *
     * @param int $id
     * @param array $data
     * @return Model|null
     */
    public function update(int $id, array $data)
    {
        $record = $this->getById($id);
        
        if ($record) {
            $record->update($data);
            return $record->fresh();
        }
        
        return null;
    }

    /**
     * Kayıt sil
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        $record = $this->getById($id);
        
        if ($record) {
            return $record->delete();
        }
        
        return false;
    }
}
