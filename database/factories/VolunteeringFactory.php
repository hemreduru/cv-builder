<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Volunteering>
 */
class VolunteeringFactory extends Factory
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
        
        $organizations = [
            'Red Cross',
            'UNICEF',
            'World Wildlife Fund',
            'Greenpeace',
            'Habitat for Humanity',
            'Doctors Without Borders',
            'Amnesty International',
            'Save the Children',
            'Food Bank',
            'Animal Rescue'
        ];
        
        $roles = [
            'Volunteer',
            'Coordinator',
            'Team Leader',
            'Mentor',
            'Organizer',
            'Fundraiser',
            'Educator',
            'Assistant',
            'Project Manager',
            'Supporter'
        ];
        
        return [
            'user_id' => null, // Will be set by seeder
            'organization' => $organizations[array_rand($organizations)],
            'role' => $roles[array_rand($roles)],
            'date' => $faker->date(),
            'description_en' => $faker->paragraph(),
            'description_tr' => $fakerTr->paragraph(),
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
