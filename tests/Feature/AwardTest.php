<?php

namespace Tests\Feature;

use App\Models\Award;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AwardTest extends TestCase
{
    use WithFaker;

    public function test_kullanici_kendi_odullerini_gorebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait 3 ödül oluşturuyoruz
        Award::factory()->count(3)->create([
            'user_id' => $user->id
        ]);
        
        // Başka bir kullanıcıya ait 2 ödül oluşturuyoruz
        $otherUser = User::factory()->create();
        Award::factory()->count(2)->create([
            'user_id' => $otherUser->id
        ]);
        
        $response = $this->get(route('awards.index'));
        
        $response->assertStatus(200);
        
        // Sadece kendi ödüllerini görebilmeli, başkalarınınkini görmemeli
        $this->assertEquals(3, $response->viewData('awards')->count());
    }

    public function test_kullanici_odul_olusturabilir()
    {
        $user = $this->loginAsUser();
        
        $awardData = [
            'name' => 'En İyi Yazılım Ödülü',
            'organization' => 'Teknoloji Derneği',
            'date' => '2023-06-15',
            'description_tr' => 'Yenilikçi yazılım projesi için verilen ödül',
            'description_en' => 'Award given for innovative software project'
        ];
        
        $response = $this->post(route('awards.store'), $awardData);
        
        $response->assertRedirect(route('awards.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında ödül kaydının oluştuğunu kontrol et
        $this->assertDatabaseHas('awards', [
            'name' => 'En İyi Yazılım Ödülü',
            'user_id' => $user->id
        ]);
    }
    
    public function test_kullanici_kendi_odullerini_duzenleyebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait bir ödül oluştur
        $award = Award::factory()->create([
            'user_id' => $user->id,
            'name' => 'Başlangıç Ödülü',
            'organization' => 'Teknoloji Akademisi'
        ]);
        
        // Düzenleme formunu görüntüle
        $response = $this->get(route('awards.edit', $award));
        $response->assertStatus(200);
        
        // Düzenleme işlemi
        $updatedData = [
            'name' => 'Yılın En İyi Projesi Ödülü',
            'organization' => 'Teknoloji Akademisi Vakfı',
            'date' => '2023-01-20',
            'description_tr' => 'Güncellenen ödül açıklaması',
            'description_en' => 'Updated award description'
        ];
        
        $response = $this->put(route('awards.update', $award), $updatedData);
        
        $response->assertRedirect(route('awards.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında güncellendiğini kontrol et
        $this->assertDatabaseHas('awards', [
            'id' => $award->id,
            'name' => 'Yılın En İyi Projesi Ödülü',
            'user_id' => $user->id
        ]);
    }
    
    public function test_kullanici_baskasinin_odullerini_duzenleyemez()
    {
        $user = $this->loginAsUser();
        $otherUser = User::factory()->create();
        
        // Başka bir kullanıcıya ait ödül oluştur
        $award = Award::factory()->create([
            'user_id' => $otherUser->id
        ]);
        
        // Düzenleme sayfasına erişim dene
        $response = $this->get(route('awards.edit', $award));
        $response->assertStatus(403);
        
        // Düzenleme işlemi dene
        $updatedData = [
            'name' => 'Güncellenmiş Ödül',
            'organization' => 'Güncel Kurum',
            'date' => '2024-01-01',
            'description_tr' => 'Güncellenmiş açıklama',
            'description_en' => 'Updated description'
        ];
        
        $response = $this->put(route('awards.update', $award), $updatedData);
        $response->assertStatus(403);
        
        // Veritabanında değişmediğini kontrol et
        $this->assertDatabaseMissing('awards', [
            'id' => $award->id,
            'name' => 'Güncellenmiş Ödül'
        ]);
    }
    
    public function test_kullanici_kendi_odullerini_silebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait bir ödül oluştur
        $award = Award::factory()->create([
            'user_id' => $user->id
        ]);
        
        $response = $this->delete(route('awards.destroy', $award));
        
        $response->assertRedirect(route('awards.index'));
        $response->assertSessionHas('success');
        
        // Veritabanından silindiğini kontrol et
        $this->assertDatabaseMissing('awards', [
            'id' => $award->id
        ]);
    }
    
    public function test_kullanici_baskasinin_odullerini_silemez()
    {
        $user = $this->loginAsUser();
        $otherUser = User::factory()->create();
        
        // Başka bir kullanıcıya ait ödül oluştur
        $award = Award::factory()->create([
            'user_id' => $otherUser->id
        ]);
        
        $response = $this->delete(route('awards.destroy', $award));
        
        $response->assertStatus(403);
        
        // Veritabanından silinmediğini kontrol et
        $this->assertDatabaseHas('awards', [
            'id' => $award->id
        ]);
    }
    
    public function test_admin_tum_odulleri_gorebilir()
    {
        $admin = $this->loginAsAdmin();
        
        // 2 normal kullanıcı oluştur ve her birine 2 ödül ekle
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Award::factory()->count(2)->create(['user_id' => $user1->id]);
        Award::factory()->count(2)->create(['user_id' => $user2->id]);
        
        $response = $this->get(route('awards.index'));
        
        $response->assertStatus(200);
        
        // Admin tüm ödülleri görebilmeli (4 tane)
        $this->assertEquals(4, $response->viewData('awards')->count());
    }
    
    public function test_admin_baska_kullanicinin_odullerini_duzenleyebilir()
    {
        $admin = $this->loginAsAdmin();
        $user = User::factory()->create();
        
        // Kullanıcıya ait bir ödül oluştur
        $award = Award::factory()->create([
            'user_id' => $user->id
        ]);
        
        // Düzenleme sayfasına erişim
        $response = $this->get(route('awards.edit', $award));
        $response->assertStatus(200);
        
        // Düzenleme işlemi
        $updatedData = [
            'name' => 'Admin Tarafından Güncellendi',
            'organization' => 'Admin Kurumu',
            'date' => '2024-06-01',
            'description_tr' => 'Admin tarafından güncellenen açıklama',
            'description_en' => 'Description updated by admin'
        ];
        
        $response = $this->put(route('awards.update', $award), $updatedData);
        
        $response->assertRedirect(route('awards.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında güncellendiğini kontrol et
        $this->assertDatabaseHas('awards', [
            'id' => $award->id,
            'name' => 'Admin Tarafından Güncellendi'
        ]);
    }
}
