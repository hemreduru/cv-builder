<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
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
            'title_en' => $faker->jobTitle(),
            'title_tr' => $fakerTr->jobTitle(),
            'bio_en' => $faker->paragraph(),
            'bio_tr' => $fakerTr->paragraph(),
            'phone' => $faker->phoneNumber(),
            'address' => $faker->address(),
            'image' => 'profile/default.jpg',
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
