<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AwardService;
use App\Services\CertificateService;
use App\Services\EducationService;
use App\Services\ExperienceService;
use App\Services\HobbyService;
use App\Services\LanguageService;
use App\Services\ProfileService;
use App\Services\ProjectService;
use App\Services\PublicationService;
use App\Services\ReferenceService;
use App\Services\SkillService;
use App\Services\SocialLinkService;
use App\Services\VolunteeringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PortfolioController extends Controller
{
    protected $profileService;
    protected $experienceService;
    protected $educationService;
    protected $projectService;
    protected $skillService;
    protected $languageService;
    protected $certificateService;
    protected $awardService;
    protected $referenceService;
    protected $hobbyService;
    protected $volunteeringService;
    protected $publicationService;
    protected $socialLinkService;

    /**
     * PortfolioController constructor.
     */
    public function __construct(
        ProfileService $profileService,
        ExperienceService $experienceService,
        EducationService $educationService,
        ProjectService $projectService,
        SkillService $skillService,
        LanguageService $languageService,
        CertificateService $certificateService,
        AwardService $awardService,
        ReferenceService $referenceService,
        HobbyService $hobbyService,
        VolunteeringService $volunteeringService,
        PublicationService $publicationService,
        SocialLinkService $socialLinkService
    ) {
        $this->profileService = $profileService;
        $this->experienceService = $experienceService;
        $this->educationService = $educationService;
        $this->projectService = $projectService;
        $this->skillService = $skillService;
        $this->languageService = $languageService;
        $this->certificateService = $certificateService;
        $this->awardService = $awardService;
        $this->referenceService = $referenceService;
        $this->hobbyService = $hobbyService;
        $this->volunteeringService = $volunteeringService;
        $this->publicationService = $publicationService;
        $this->socialLinkService = $socialLinkService;
    }

    /**
     * Kullanıcının portfolyo sayfasını göster
     *
     * @param string $username
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $username)
    {
        $user = User::where('name', $username)->first();
        
        if (!$user) {
            return redirect()->route('home')->with('error', __('app.user_not_found'));
        }
        
        $userId = $user->id;
        
        $data = [
            'user' => $user,
            'profile' => $this->profileService->getByUserId($userId),
            'experiences' => $this->experienceService->getUserExperiences($userId),
            'educations' => $this->educationService->getUserEducations($userId),
            'featuredProjects' => $this->projectService->getUserFeaturedProjects($userId),
            'projects' => $this->projectService->getUserProjects($userId),
            'skillsByCategory' => $this->skillService->getUserSkillsByCategory($userId),
            'languages' => $this->languageService->getUserLanguages($userId),
            'certificates' => $this->certificateService->getUserCertificates($userId),
            'awards' => $this->awardService->getUserAwards($userId),
            'references' => $this->referenceService->getUserReferences($userId),
            'hobbies' => $this->hobbyService->getUserHobbies($userId),
            'volunteerings' => $this->volunteeringService->getUserVolunteerings($userId),
            'publications' => $this->publicationService->getUserPublications($userId),
            'socialLinks' => $this->socialLinkService->getUserSocialLinks($userId),
        ];
        
        return view('portfolio.show', $data);
    }
}
