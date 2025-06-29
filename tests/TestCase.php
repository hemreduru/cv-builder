<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\LaratrustTestSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laratrust\Laratrust;
use Tests\Feature\TestRouteProvider;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase, TestRouteProvider;
    
    /**
     * Test çalıştırılmadan önce gerekli hazırlıkları yap
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Laratrust rolleri ve izinleri için seed çalıştır
        $this->seed(LaratrustTestSeeder::class);
        
        // Test rotalarını tanımla
        $this->defineRoutes();
    }
    
    /**
     * Normal kullanıcı oluştur
     *
     * @param array $attributes
     * @return User
     */
    protected function createUser(array $attributes = [])
    {
        $user = User::factory()->create($attributes);
        $user->addRole('user');
        return $user;
    }
    
    /**
     * Admin kullanıcısı oluştur
     *
     * @param array $attributes
     * @return User
     */
    protected function createAdmin(array $attributes = [])
    {
        $user = User::factory()->create($attributes);
        $user->addRole('admin');
        return $user;
    }
    
    /**
     * İzinleri test etmek için kullanıcıyı giriş yaptır
     *
     * @param User|null $user
     * @return User
     */
    protected function loginAsUser(User $user = null)
    {
        $user = $user ?? $this->createUser();
        $this->actingAs($user);
        return $user;
    }
    
    /**
     * Admin olarak giriş yap
     *
     * @param User|null $admin
     * @return User
     */
    protected function loginAsAdmin(User $admin = null)
    {
        $admin = $admin ?? $this->createAdmin();
        $this->actingAs($admin);
        return $admin;
    }
}
