<?php

namespace App\Services;

use App\Models\Language;
use Illuminate\Support\Facades\Auth;

class LanguageService extends BaseService
{
    /**
     * LanguageService constructor.
     *
     * @param Language $language
     */
    public function __construct(Language $language)
    {
        parent::__construct($language);
    }

    /**
     * Yeni dil kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Language
     */
    public function createLanguage(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Dil kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Language|null
     */
    public function updateLanguage(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait dil kayıtları listesi
     * (Seviyeye göre tersten sıralı)
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserLanguages(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('level', 'DESC');
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Dil kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteLanguage(int $id)
    {
        return $this->delete($id);
    }
}
