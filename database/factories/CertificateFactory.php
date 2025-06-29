<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('en');
        $fakerTr = \Faker\Factory::create('tr');
        
        $certifications = [
            'AWS Certified Solutions Architect',
            'Microsoft Certified: Azure Developer Associate',
            'Google Cloud Professional Data Engineer',
            'Certified Information Systems Security Professional (CISSP)',
            'Project Management Professional (PMP)',
            'Certified ScrumMaster (CSM)',
            'Certified Web Developer',
            'Cisco Certified Network Associate (CCNA)',
            'CompTIA A+',
            'Oracle Certified Professional, Java SE Programmer'
        ];
        
        $issuers = [
            'Amazon Web Services',
            'Microsoft',
            'Google',
            'ISC2',
            'Project Management Institute',
            'Scrum Alliance',
            'W3Schools',
            'Cisco',
            'CompTIA',
            'Oracle'
        ];
        
        $certIndex = array_rand($certifications);
        
        return [
            'user_id' => null, // Will be set by seeder
            'name' => $certifications[$certIndex],
            'issuer' => $issuers[$certIndex],
            'date' => $faker->date(),
            'description_en' => $faker->paragraph(),
            'description_tr' => $fakerTr->paragraph(),
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
