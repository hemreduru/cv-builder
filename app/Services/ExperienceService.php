<?php

namespace App\Services;

use App\Models\Experience;
use Illuminate\Support\Facades\Auth;

class ExperienceService extends BaseService
{
    /**
     * ExperienceService constructor.
     *
     * @param Experience $experience
     */
    public function __construct(Experience $experience)
    {
        parent::__construct($experience);
    }

    /**
     * Yeni deneyim kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Experience
     */
    public function createExperience(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Deneyim kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Experience|null
     */
    public function updateExperience(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait deneyimler listesi
     * (Başlangıç tarihine göre tersten sıralı)
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserExperiences(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('start_date', 'DESC');
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Deneyim kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteExperience(int $id)
    {
        return $this->delete($id);
    }
}
