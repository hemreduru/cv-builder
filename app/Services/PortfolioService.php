<?php

namespace App\Services;

use App\Models\User;
use App\Models\Profile;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Language;
use App\Models\Certificate;
use App\Models\Award;
use App\Models\SocialLink;
use App\Models\Hobby;
use App\Models\Volunteering;
use App\Models\Publication;

class PortfolioService
{
    /**
     * Kullanıcının tüm portfolio verilerini getir
     * 
     * @param int $userId
     * @return array
     */
    public function getUserPortfolioData(int $userId): array
    {
        $user = User::findOrFail($userId);
        $locale = app()->getLocale();
        
        // Profil bilgileri
        $profile = $this->getProfileData($user);
        
        // Deneyimler
        $experiences = $this->getExperienceData($user);
        
        // Eğitim bilgileri
        $educations = $this->getEducationData($user);
        
        // Öne çıkan projeler (maksimum 5)
        $projects = $this->getProjectData($user, 5, true);
        
        // Yetenekler
        $skills = $this->getSkillData($user);
        
        // Diller
        $languages = $this->getLanguageData($user);
        
        // Sertifikalar
        $certificates = $this->getCertificateData($user);
        
        // Ödüller
        $awards = $this->getAwardData($user);
        
        // Sosyal bağlantılar
        $socialLinks = $this->getSocialLinkData($user);
        
        // Hobiler
        $hobbies = $this->getHobbyData($user);
        
        // Gönüllü çalışmalar
        $volunteerings = $this->getVolunteeringData($user);
        
        // Yayınlar
        $publications = $this->getPublicationData($user);
        
        return [
            'user' => $user,
            'profile' => $profile,
            'experiences' => $experiences,
            'educations' => $educations,
            'projects' => $projects,
            'skills' => $skills,
            'languages' => $languages,
            'certificates' => $certificates,
            'awards' => $awards,
            'socialLinks' => $socialLinks,
            'hobbies' => $hobbies,
            'volunteerings' => $volunteerings,
            'publications' => $publications,
            'locale' => $locale,
        ];
    }
    
    /**
     * Profil bilgilerini getir
     * 
     * @param User $user
     * @return Profile|null
     */
    private function getProfileData(User $user)
    {
        return $user->profile;
    }
    
    /**
     * Deneyim bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getExperienceData(User $user)
    {
        return $user->experiences()
            ->orderBy('start_date', 'desc')
            ->get();
    }
    
    /**
     * Eğitim bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getEducationData(User $user)
    {
        return $user->education()
            ->orderBy('start_date', 'desc')
            ->get();
    }
    
    /**
     * Proje bilgilerini getir
     * 
     * @param User $user
     * @param int|null $limit
     * @param bool $onlyFeatured
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getProjectData(User $user, ?int $limit = null, bool $onlyFeatured = false)
    {
        $query = $user->projects();
        
        if ($onlyFeatured) {
            $query->where('is_featured', true);
        }
        
        $query->orderBy('created_at', 'desc');
        
        if ($limit) {
            return $query->take($limit)->get();
        }
        
        return $query->get();
    }
    
    /**
     * Yetenek bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSkillData(User $user)
    {
        return $user->skills()
            ->orderBy('level', 'desc')
            ->get()
            ->groupBy('category');
    }
    
    /**
     * Dil bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getLanguageData(User $user)
    {
        return $user->languages()
            ->orderBy('level', 'desc')
            ->get();
    }
    
    /**
     * Sertifika bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getCertificateData(User $user)
    {
        return $user->certificates()
            ->orderBy('date', 'desc')
            ->get();
    }
    
    /**
     * Ödül bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getAwardData(User $user)
    {
        return $user->awards()
            ->orderBy('date', 'desc')
            ->get();
    }
    
    /**
     * Sosyal bağlantıları getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getSocialLinkData(User $user)
    {
        return $user->socialLinks()->get();
    }
    
    /**
     * Hobi bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getHobbyData(User $user)
    {
        return $user->hobbies()->get();
    }
    
    /**
     * Gönüllü çalışma bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getVolunteeringData(User $user)
    {
        return $user->volunteerings()
            ->orderBy('date', 'desc')
            ->get();
    }
    
    /**
     * Yayın bilgilerini getir
     * 
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getPublicationData(User $user)
    {
        return $user->publications()->get();
    }
}
