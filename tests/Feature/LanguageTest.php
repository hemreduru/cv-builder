<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    use WithFaker;

    public function test_kullanici_kendi_dillerini_gorebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait 3 dil oluşturuyoruz
        Language::factory()->count(3)->create([
            'user_id' => $user->id
        ]);
        
        // Başka bir kullanıcıya ait 2 dil oluşturuyoruz
        $otherUser = User::factory()->create();
        Language::factory()->count(2)->create([
            'user_id' => $otherUser->id
        ]);
        
        $response = $this->get(route('languages.index'));
        
        $response->assertStatus(200);
        
        // Sadece kendi dillerini görebilmeli, başkalarınınkini görmemeli
        $this->assertEquals(3, $response->viewData('languages')->count());
    }

    public function test_kullanici_dil_olusturabilir()
    {
        $user = $this->loginAsUser();
        
        $languageData = [
            'name_tr' => 'İngilizce',
            'name_en' => 'English',
            'level' => 'Akıcı'
        ];
        
        $response = $this->post(route('languages.store'), $languageData);
        
        $response->assertRedirect(route('languages.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında dil kaydının oluştuğunu kontrol et
        $this->assertDatabaseHas('languages', [
            'name_tr' => 'İngilizce',
            'user_id' => $user->id
        ]);
    }
    
    public function test_kullanici_kendi_dillerini_duzenleyebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait bir dil oluştur
        $language = Language::factory()->create([
            'user_id' => $user->id,
            'name_tr' => 'Almanca',
            'name_en' => 'German'
        ]);
        
        // Düzenleme formunu görüntüle
        $response = $this->get(route('languages.edit', $language));
        $response->assertStatus(200);
        
        // Düzenleme işlemi
        $updatedData = [
            'name_tr' => 'Almanca (İleri)',
            'name_en' => 'German (Advanced)',
            'level' => 'C1'
        ];
        
        $response = $this->put(route('languages.update', $language), $updatedData);
        
        $response->assertRedirect(route('languages.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında güncellendiğini kontrol et
        $this->assertDatabaseHas('languages', [
            'id' => $language->id,
            'name_tr' => 'Almanca (İleri)',
            'user_id' => $user->id
        ]);
    }
    
    public function test_kullanici_baskasinin_dillerini_duzenleyemez()
    {
        $user = $this->loginAsUser();
        $otherUser = User::factory()->create();
        
        // Başka bir kullanıcıya ait dil oluştur
        $language = Language::factory()->create([
            'user_id' => $otherUser->id
        ]);
        
        // Düzenleme sayfasına erişim dene
        $response = $this->get(route('languages.edit', $language));
        $response->assertStatus(403);
        
        // Düzenleme işlemi dene
        $updatedData = [
            'name_tr' => 'Güncellenmiş Dil',
            'name_en' => 'Updated Language',
            'level' => 'B2'
        ];
        
        $response = $this->put(route('languages.update', $language), $updatedData);
        $response->assertStatus(403);
        
        // Veritabanında değişmediğini kontrol et
        $this->assertDatabaseMissing('languages', [
            'id' => $language->id,
            'name_tr' => 'Güncellenmiş Dil'
        ]);
    }
    
    public function test_kullanici_kendi_dillerini_silebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait bir dil oluştur
        $language = Language::factory()->create([
            'user_id' => $user->id
        ]);
        
        $response = $this->delete(route('languages.destroy', $language));
        
        $response->assertRedirect(route('languages.index'));
        $response->assertSessionHas('success');
        
        // Veritabanından silindiğini kontrol et
        $this->assertDatabaseMissing('languages', [
            'id' => $language->id
        ]);
    }
    
    public function test_kullanici_baskasinin_dillerini_silemez()
    {
        $user = $this->loginAsUser();
        $otherUser = User::factory()->create();
        
        // Başka bir kullanıcıya ait dil oluştur
        $language = Language::factory()->create([
            'user_id' => $otherUser->id
        ]);
        
        $response = $this->delete(route('languages.destroy', $language));
        
        $response->assertStatus(403);
        
        // Veritabanından silinmediğini kontrol et
        $this->assertDatabaseHas('languages', [
            'id' => $language->id
        ]);
    }
    
    public function test_admin_tum_dilleri_gorebilir()
    {
        $admin = $this->loginAsAdmin();
        
        // 2 normal kullanıcı oluştur ve her birine 2 dil ekle
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Language::factory()->count(2)->create(['user_id' => $user1->id]);
        Language::factory()->count(2)->create(['user_id' => $user2->id]);
        
        $response = $this->get(route('languages.index'));
        
        $response->assertStatus(200);
        
        // Admin tüm dilleri görebilmeli (4 tane)
        $this->assertEquals(4, $response->viewData('languages')->count());
    }
    
    public function test_admin_baska_kullanicinin_dillerini_duzenleyebilir()
    {
        $admin = $this->loginAsAdmin();
        $user = User::factory()->create();
        
        // Kullanıcıya ait bir dil oluştur
        $language = Language::factory()->create([
            'user_id' => $user->id
        ]);
        
        // Düzenleme sayfasına erişim
        $response = $this->get(route('languages.edit', $language));
        $response->assertStatus(200);
        
        // Düzenleme işlemi
        $updatedData = [
            'name_tr' => 'Admin Tarafından Güncellendi',
            'name_en' => 'Updated By Admin',
            'level' => 'C2'
        ];
        
        $response = $this->put(route('languages.update', $language), $updatedData);
        
        $response->assertRedirect(route('languages.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında güncellendiğini kontrol et
        $this->assertDatabaseHas('languages', [
            'id' => $language->id,
            'name_tr' => 'Admin Tarafından Güncellendi'
        ]);
    }
}
