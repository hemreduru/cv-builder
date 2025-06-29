<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Publication>
 */
class PublicationFactory extends Factory
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
        
        $publications = [
            'The Future of Web Development',
            'Understanding Machine Learning Algorithms',
            'Modern Database Design Patterns',
            'User Experience in Mobile Applications',
            'Cloud Computing Architecture',
            'Blockchain Technology and Its Applications',
            'Artificial Intelligence in Healthcare',
            'Cybersecurity Best Practices',
            'Agile Development Methodologies',
            'The Impact of IoT on Business Operations'
        ];
        
        return [
            'user_id' => null, // Will be set by seeder
            'title' => $publications[array_rand($publications)],
            'url' => $faker->url(),
            'summary_en' => $faker->paragraph(),
            'summary_tr' => $fakerTr->paragraph(),
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
