<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Education>
 */
class EducationFactory extends Factory
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
            'school_name' => $faker->company() . ' University',
            'department' => $faker->jobTitle(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
            'description_en' => $faker->paragraphs(rand(1, 2), true),
            'description_tr' => $fakerTr->paragraphs(rand(1, 2), true),
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
