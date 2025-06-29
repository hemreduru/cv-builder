<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProfileSeeder;
use Database\Seeders\ExperienceSeeder;
use Database\Seeders\EducationSeeder;
use Database\Seeders\ProjectSeeder;
use Database\Seeders\SkillSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\CertificateSeeder;
use Database\Seeders\AwardSeeder;
use Database\Seeders\ReferenceSeeder;
use Database\Seeders\HobbySeeder;
use Database\Seeders\SocialLinkSeeder;
use Database\Seeders\VolunteeringSeeder;
use Database\Seeders\PublicationSeeder;
use Database\Seeders\LaratrustSeeder;
use Database\Seeders\UserRoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Önce Laratrust rol ve izinlerini ekleyelim
        $this->call(LaratrustSeeder::class);
        
        // Ardından özel kullanıcılar ve rolleri
        $this->call(UserRoleSeeder::class);
        
        // Diğer tüm seeder'lar
        $this->call([
            UserSeeder::class,
            ProfileSeeder::class,
            ExperienceSeeder::class,
            EducationSeeder::class,
            ProjectSeeder::class,
            SkillSeeder::class,
            LanguageSeeder::class,
            CertificateSeeder::class,
            AwardSeeder::class,
            ReferenceSeeder::class,
            HobbySeeder::class,
            SocialLinkSeeder::class,
            VolunteeringSeeder::class,
            PublicationSeeder::class,
        ]);
    }
}
