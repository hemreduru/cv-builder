<?php

namespace App\Services;

use App\Models\Hobby;
use Illuminate\Support\Facades\Auth;

class HobbyService extends BaseService
{
    /**
     * HobbyService constructor.
     *
     * @param Hobby $hobby
     */
    public function __construct(Hobby $hobby)
    {
        parent::__construct($hobby);
    }

    /**
     * Yeni hobi kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Hobby
     */
    public function createHobby(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Hobi kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Hobby|null
     */
    public function updateHobby(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait hobi kayıtları listesi
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserHobbies(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId);
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Hobi kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteHobby(int $id)
    {
        return $this->delete($id);
    }
}
