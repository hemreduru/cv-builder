<?php

namespace App\Services;

use App\Models\Publication;
use Illuminate\Support\Facades\Auth;

class PublicationService extends BaseService
{
    /**
     * PublicationService constructor.
     *
     * @param Publication $publication
     */
    public function __construct(Publication $publication)
    {
        parent::__construct($publication);
    }

    /**
     * Yeni yayın kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Publication
     */
    public function createPublication(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Yayın kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Publication|null
     */
    public function updatePublication(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait yayın kayıtları listesi
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserPublications(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('title');
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Yayın kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deletePublication(int $id)
    {
        return $this->delete($id);
    }
}
