<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Award>
 */
class AwardFactory extends Factory
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
        
        $awards = [
            'Best Developer Award',
            'Innovation Prize',
            'Excellence in Technology Award',
            'Outstanding Performance Award',
            'Leadership Recognition Award',
            'Employee of the Year',
            'Best Project Award',
            'Customer Satisfaction Award',
            'Team Collaboration Award',
            'Creative Solution Award'
        ];
        
        $organizations = [
            'Tech Summit',
            'Technology Association',
            'Innovation Forum',
            'Digital Excellence Awards',
            'IT Leaders Association',
            'Developer Conference',
            'Enterprise Technology Forum',
            'Industry Leaders Summit',
            'Tech Excellence Organization',
            'Digital Innovation Council'
        ];
        
        return [
            'user_id' => null, // Will be set by seeder
            'name' => $awards[array_rand($awards)],
            'organization' => $organizations[array_rand($organizations)],
            'date' => $faker->date(),
            'description_en' => $faker->paragraph(),
            'description_tr' => $fakerTr->paragraph(),
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
