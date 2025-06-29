<?php

namespace App\Services;

use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;

class CertificateService extends BaseService
{
    /**
     * CertificateService constructor.
     *
     * @param Certificate $certificate
     */
    public function __construct(Certificate $certificate)
    {
        parent::__construct($certificate);
    }

    /**
     * Yeni sertifika kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Certificate
     */
    public function createCertificate(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Sertifika kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Certificate|null
     */
    public function updateCertificate(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait sertifika kayıtları listesi
     * (Tarihe göre tersten sıralı)
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserCertificates(int $userId, array $relations = [])
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
     * Sertifika kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteCertificate(int $id)
    {
        return $this->delete($id);
    }
}
