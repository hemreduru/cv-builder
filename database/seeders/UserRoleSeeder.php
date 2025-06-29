<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Admin kullanıcısı oluştur
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'surname' => 'User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // Admin rolünü ekle
        $admin->addRole('admin');
        
        // Normal kullanıcı oluştur
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal',
                'surname' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        
        // User rolünü ekle
        $user->addRole('user');
        
        // Birkaç test kullanıcısı daha oluştur
        for ($i = 1; $i <= 3; $i++) {
            $testUser = User::firstOrCreate(
                ['email' => "test{$i}@example.com"],
                [
                    'name' => "Test{$i}",
                    'surname' => "User{$i}",
                    'email' => "test{$i}@example.com",
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            
            $testUser->addRole('user');
        }
    }
}
