<?php

namespace App\Services;

use App\Models\User;
use App\Models\Profile;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Language;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;

class CVService
{
    /**
     * Kullanıcının CV verilerini getir
     * 
     * @param int $userId
     * @param string $locale
     * @return array
     */
    public function getUserCVData(int $userId, string $locale = null): array
    {
        if ($locale) {
            app()->setLocale($locale);
        } else {
            $locale = app()->getLocale();
        }
        
        $user = User::findOrFail($userId);
        
        // Profil bilgileri
        $profile = $user->profile;
        
        // Deneyimler
        $experiences = $user->experiences()
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Eğitim bilgileri
        $educations = $user->education()
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Öne çıkan projeler (maksimum 5)
        $projects = $user->projects()
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Yetenekler
        $skills = $user->skills()
            ->orderBy('level', 'desc')
            ->get()
            ->groupBy('category');
        
        // Diller
        $languages = $user->languages()
            ->orderBy('level', 'desc')
            ->get();
        
        return [
            'user' => $user,
            'profile' => $profile,
            'experiences' => $experiences,
            'educations' => $educations,
            'projects' => $projects,
            'skills' => $skills,
            'languages' => $languages,
            'locale' => $locale,
            'meta' => [
                'title' => __('cv.document_title', ['name' => $user->name . ' ' . $user->surname]),
                'subject' => __('cv.document_subject'),
                'author' => $user->name . ' ' . $user->surname,
                'creator' => config('app.name'),
                'keywords' => __('cv.document_keywords')
            ]
        ];
    }
    
    /**
     * Kullanıcının CV'sini PDF olarak oluştur
     * 
     * @param int $userId
     * @param string $locale
     * @return string
     */
    public function generateUserCVPdf(int $userId, string $locale = null): string
    {
        $data = $this->getUserCVData($userId, $locale);
        
        $viewName = 'pdf.cv.' . $data['locale']; // Örn: pdf.cv.tr veya pdf.cv.en
        
        // Eğer belirtilen dilde şablon yoksa varsayılan dilde kullan
        if (!View::exists($viewName)) {
            $viewName = 'pdf.cv.' . config('app.fallback_locale');
        }
        
        $pdf = Pdf::loadView($viewName, $data);
        
        // Sayfa boyutu ve yönlendirme ayarları
        $pdf->setPaper('a4', 'portrait');
        
        // PDF meta bilgileri
        $pdf->setOption('title', $data['meta']['title']);
        $pdf->setOption('subject', $data['meta']['subject']);
        $pdf->setOption('author', $data['meta']['author']);
        $pdf->setOption('creator', $data['meta']['creator']);
        $pdf->setOption('keywords', $data['meta']['keywords']);
        
        // PDF'i string olarak döndür
        return $pdf->output();
    }
    
    /**
     * Boş alan veya bölümleri kontrol et
     * Bir bölüm tamamen boşsa (hem TR hem EN içerik yoksa) gizlenecek
     * 
     * @param array $data
     * @return array
     */
    public function filterEmptySections(array $data): array
    {
        $result = $data;
        
        // Her bir collection için kontrol et
        foreach (['experiences', 'educations', 'projects', 'skills', 'languages'] as $section) {
            if (empty($data[$section]) || $data[$section]->isEmpty()) {
                $result[$section] = null;
            }
        }
        
        // Profil için ayrıca kontrol et
        if (empty($data['profile'])) {
            $result['profile'] = null;
        }
        
        return $result;
    }
    
    /**
     * Dil içeriği için yardımcı metot
     * Eğer ana dildeki içerik yoksa, yedek dildeki içeriği kullan
     * 
     * @param object $model
     * @param string $field
     * @return string|null
     */
    public function getLocalizedContent($model, string $field): ?string
    {
        $locale = app()->getLocale();
        $fallbackLocale = config('app.fallback_locale');
        
        // Alanın dile özgü versiyonlarını oluştur (örn: description_tr, description_en)
        $localeField = $field . '_' . $locale;
        $fallbackField = $field . '_' . $fallbackLocale;
        
        // Önce mevcut dildeki içeriği kontrol et
        if (!empty($model->$localeField)) {
            return $model->$localeField;
        }
        
        // Yoksa yedek dildeki içeriği kontrol et
        if (!empty($model->$fallbackField)) {
            return $model->$fallbackField;
        }
        
        return null;
    }
}
