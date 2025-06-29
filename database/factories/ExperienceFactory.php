<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Experience>
 */
class ExperienceFactory extends Factory
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
        
        $startDate = $faker->dateTimeBetween('-10 years', '-1 year');
        $endDate = rand(0, 10) > 2 ? $faker->dateTimeBetween($startDate, 'now') : null;
        
        return [
            'user_id' => null, // Will be set by seeder
            'job_title_en' => $faker->jobTitle(),
            'job_title_tr' => $fakerTr->jobTitle(),
            'company_name' => $faker->company(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
            'description_en' => $faker->paragraphs(rand(1, 3), true),
            'description_tr' => $fakerTr->paragraphs(rand(1, 3), true),
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
