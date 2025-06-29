<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SocialLink>
 */
class SocialLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('en');
        
        $platforms = [
            'linkedin' => 'https://www.linkedin.com/in/',
            'github' => 'https://github.com/',
            'twitter' => 'https://twitter.com/',
            'instagram' => 'https://www.instagram.com/',
            'facebook' => 'https://www.facebook.com/',
            'youtube' => 'https://www.youtube.com/c/',
            'medium' => 'https://medium.com/@',
            'dribbble' => 'https://dribbble.com/',
            'behance' => 'https://www.behance.net/'
        ];
        
        $type = array_rand($platforms);
        $username = strtolower(str_replace([' ', '.'], ['', ''], $faker->userName()));
        
        return [
            'user_id' => null, // Will be set by seeder
            'type' => $type,
            'url' => $platforms[$type] . $username,
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
