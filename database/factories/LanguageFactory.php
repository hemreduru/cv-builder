<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
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
        
        $languages = [
            'en' => ['English', 'İngilizce'],
            'tr' => ['Turkish', 'Türkçe'],
            'fr' => ['French', 'Fransızca'],
            'de' => ['German', 'Almanca'],
            'es' => ['Spanish', 'İspanyolca'],
            'it' => ['Italian', 'İtalyanca'],
            'ru' => ['Russian', 'Rusça'],
            'ja' => ['Japanese', 'Japonca'],
            'zh' => ['Chinese', 'Çince'],
            'ar' => ['Arabic', 'Arapça'],
        ];
        
        $lang = array_rand($languages);
        
        // Convert language proficiency to numeric values (1-5 scale)
        // Using 1-5 scale as more common for language proficiency levels
        
        return [
            'user_id' => null, // Will be set by seeder
            'name_en' => $languages[$lang][0],
            'name_tr' => $languages[$lang][1],
            'level' => rand(1, 5), // Numeric value from 1-5 as per migration
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
