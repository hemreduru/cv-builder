<?php

namespace App\Services;

use App\Models\Reference;
use Illuminate\Support\Facades\Auth;

class ReferenceService extends BaseService
{
    /**
     * ReferenceService constructor.
     *
     * @param Reference $reference
     */
    public function __construct(Reference $reference)
    {
        parent::__construct($reference);
    }

    /**
     * Yeni referans kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Reference
     */
    public function createReference(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Referans kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Reference|null
     */
    public function updateReference(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait referans kayıtları listesi
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserReferences(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('name');
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Referans kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteReference(int $id)
    {
        return $this->delete($id);
    }
}
