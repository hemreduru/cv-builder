<?php

namespace App\Services;

use App\Models\SocialLink;
use Illuminate\Support\Facades\Auth;

class SocialLinkService extends BaseService
{
    /**
     * SocialLinkService constructor.
     *
     * @param SocialLink $socialLink
     */
    public function __construct(SocialLink $socialLink)
    {
        parent::__construct($socialLink);
    }

    /**
     * Yeni sosyal medya bağlantısı kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\SocialLink
     */
    public function createSocialLink(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Sosyal medya bağlantısı kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\SocialLink|null
     */
    public function updateSocialLink(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait sosyal medya bağlantıları listesi
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserSocialLinks(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('type');
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Sosyal medya bağlantısı kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteSocialLink(int $id)
    {
        return $this->delete($id);
    }
}
