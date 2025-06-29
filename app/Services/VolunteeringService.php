<?php

namespace App\Services;

use App\Models\Volunteering;
use Illuminate\Support\Facades\Auth;

class VolunteeringService extends BaseService
{
    /**
     * VolunteeringService constructor.
     *
     * @param Volunteering $volunteering
     */
    public function __construct(Volunteering $volunteering)
    {
        parent::__construct($volunteering);
    }

    /**
     * Yeni gönüllülük kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Volunteering
     */
    public function createVolunteering(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Gönüllülük kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Volunteering|null
     */
    public function updateVolunteering(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait gönüllülük kayıtları listesi
     * (Tarihe göre tersten sıralı)
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserVolunteerings(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('date', 'DESC');
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Gönüllülük kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteVolunteering(int $id)
    {
        return $this->delete($id);
    }
}
