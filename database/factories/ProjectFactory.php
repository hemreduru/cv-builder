<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
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
        
        return [
            'user_id' => null, // Will be set by seeder
            'title' => $faker->sentence(3),
            'image' => 'projects/project-' . rand(1, 5) . '.jpg',
            'url' => $faker->url(),
            'description_en' => $faker->paragraphs(rand(1, 3), true),
            'description_tr' => $fakerTr->paragraphs(rand(1, 3), true),
            'is_featured' => rand(0, 1),
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
