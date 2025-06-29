<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hobby>
 */
class HobbyFactory extends Factory
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
        
        $hobbies = [
            ['Photography', 'Fotoğrafçılık'],
            ['Playing Guitar', 'Gitar Çalmak'],
            ['Cooking', 'Yemek Yapmak'],
            ['Traveling', 'Seyahat Etmek'],
            ['Reading', 'Kitap Okumak'],
            ['Swimming', 'Yüzmek'],
            ['Hiking', 'Doğa Yürüyüşü'],
            ['Painting', 'Resim Yapmak'],
            ['Gaming', 'Oyun Oynamak'],
            ['Cycling', 'Bisiklet Sürmek'],
            ['Yoga', 'Yoga'],
            ['Gardening', 'Bahçe İşleri'],
            ['Dancing', 'Dans Etmek'],
            ['Writing', 'Yazı Yazmak'],
            ['Chess', 'Satranç']
        ];
        
        $hobbyIndex = array_rand($hobbies);
        
        return [
            'user_id' => null, // Will be set by seeder
            'name_en' => $hobbies[$hobbyIndex][0],
            'name_tr' => $hobbies[$hobbyIndex][1],
            'created_by' => null, // Will be set by seeder
            'updated_by' => null,
        ];
    }
}
