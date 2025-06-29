<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reference>
 */
class ReferenceFactory extends Factory
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
            'name' => $faker->name(),
            'company' => $faker->company(),
            'contact' => $faker->email(),
            'note_en' => $faker->sentence(15),
            'note_tr' => $fakerTr->sentence(15),
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
