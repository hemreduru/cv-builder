<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
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
        
        $categories = ['Programming', 'Design', 'Language', 'Management', 'Marketing', 'Analysis'];
        $skillNames = [
            'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'HTML', 'CSS', 'MySQL', 
            'Git', 'Docker', 'AWS', 'UI/UX Design', 'Photoshop', 'Illustrator', 'SEO'
        ];
        
        $randomSkill = $skillNames[array_rand($skillNames)];
        
        return [
            'user_id' => null, // Will be set by seeder
            'name_en' => $randomSkill,
            'name_tr' => $randomSkill, // Most tech skills have the same name in Turkish
            'level' => rand(60, 100),
            'category' => $categories[array_rand($categories)],
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
