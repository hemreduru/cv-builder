<?php

namespace App\Services;

use App\Models\Award;
use Illuminate\Support\Facades\Auth;

class AwardService extends BaseService
{
    /**
     * AwardService constructor.
     *
     * @param Award $award
     */
    public function __construct(Award $award)
    {
        parent::__construct($award);
    }

    /**
     * Yeni ödül kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Award
     */
    public function createAward(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Ödül kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Award|null
     */
    public function updateAward(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait ödül kayıtları listesi
     * (Tarihe göre tersten sıralı)
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserAwards(int $userId, array $relations = [])
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
     * Ödül kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteAward(int $id)
    {
        return $this->delete($id);
    }
}
