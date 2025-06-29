<?php

namespace App\Services;

use App\Models\Education;
use Illuminate\Support\Facades\Auth;

class EducationService extends BaseService
{
    /**
     * EducationService constructor.
     *
     * @param Education $education
     */
    public function __construct(Education $education)
    {
        parent::__construct($education);
    }

    /**
     * Yeni eğitim kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Education
     */
    public function createEducation(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Eğitim kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Education|null
     */
    public function updateEducation(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait eğitim kayıtları listesi
     * (Başlangıç tarihine göre tersten sıralı)
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserEducations(int $userId, array $relations = [])
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
     * Eğitim kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteEducation(int $id)
    {
        return $this->delete($id);
    }
}
