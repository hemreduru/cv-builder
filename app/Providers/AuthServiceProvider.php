<?php

namespace App\Providers;

use App\Models\Award;
use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Hobby;
use App\Models\Language;
use App\Models\Project;
use App\Models\Profile;
use App\Models\Publication;
use App\Models\Reference;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\Volunteering;
use App\Policies\AwardPolicy;
use App\Policies\CertificatePolicy;
use App\Policies\EducationPolicy;
use App\Policies\ExperiencePolicy;
use App\Policies\HobbyPolicy;
use App\Policies\LanguagePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\ProfilePolicy;
use App\Policies\PublicationPolicy;
use App\Policies\ReferencePolicy;
use App\Policies\SkillPolicy;
use App\Policies\SocialLinkPolicy;
use App\Policies\VolunteeringPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Profile::class => ProfilePolicy::class,
        Experience::class => ExperiencePolicy::class,
        Education::class => EducationPolicy::class,
        Project::class => ProjectPolicy::class,
        Skill::class => SkillPolicy::class,
        Language::class => LanguagePolicy::class,
        Certificate::class => CertificatePolicy::class,
        Award::class => AwardPolicy::class,
        Reference::class => ReferencePolicy::class,
        Hobby::class => HobbyPolicy::class,
        Volunteering::class => VolunteeringPolicy::class,
        Publication::class => PublicationPolicy::class,
        SocialLink::class => SocialLinkPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
