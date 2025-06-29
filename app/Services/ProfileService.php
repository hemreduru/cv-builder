<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileService extends BaseService
{
    /**
     * ProfileService constructor.
     *
     * @param Profile $profile
     */
    public function __construct(Profile $profile)
    {
        parent::__construct($profile);
    }

    /**
     * Kullanıcının profilini getir veya yeni bir profil oluştur
     *
     * @param int $userId
     * @return \App\Models\Profile
     */
    public function findOrCreateForUser(int $userId)
    {
        $profile = $this->model->where('user_id', $userId)->first();
        
        if (!$profile) {
            $profile = $this->create([
                'user_id' => $userId,
                'created_by' => $userId,
                'updated_by' => $userId
            ]);
        }
        
        return $profile;
    }

    /**
     * Profil bilgilerini güncelle
     *
     * @param int $userId
     * @param array $data
     * @return \App\Models\Profile|null
     */
    public function updateProfile(int $userId, array $data)
    {
        // Kullanıcı bilgilerini ekleyelim
        $data['updated_by'] = Auth::id() ?? $userId;
        
        $profile = $this->model->where('user_id', $userId)->first();
        
        if ($profile) {
            $profile->update($data);
            return $profile->fresh();
        }
        
        // Profil yoksa, yeni bir profil oluşturalım
        $data['user_id'] = $userId;
        $data['created_by'] = Auth::id() ?? $userId;
        
        return $this->create($data);
    }
    
    /**
     * Profil resmi güncelleme
     *
     * @param int $userId
     * @param mixed $imageFile
     * @return string|null Resim yolu
     */
    public function updateProfileImage(int $userId, $imageFile)
    {
        if (!$imageFile) {
            return null;
        }
        
        $profile = $this->model->where('user_id', $userId)->first();
        
        if (!$profile) {
            return null;
        }
        
        // Resmin benzersiz bir isim almasını sağlayalım
        $filename = time() . '_' . $userId . '.' . $imageFile->getClientOriginalExtension();
        $path = $imageFile->storeAs('profile', $filename, 'public');
        
        $profile->update([
            'image' => $path,
            'updated_by' => Auth::id() ?? $userId
        ]);
        
        return $path;
    }
}
