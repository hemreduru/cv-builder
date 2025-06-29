<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use WithFaker;

    public function test_kullanici_kendi_sertifikalarini_gorebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait 3 sertifika oluşturuyoruz
        Certificate::factory()->count(3)->create([
            'user_id' => $user->id
        ]);
        
        // Başka bir kullanıcıya ait 2 sertifika oluşturuyoruz
        $otherUser = User::factory()->create();
        Certificate::factory()->count(2)->create([
            'user_id' => $otherUser->id
        ]);
        
        $response = $this->get(route('certificates.index'));
        
        $response->assertStatus(200);
        
        // Sadece kendi sertifikalarını görebilmeli, başkalarınınkini görmemeli
        $this->assertEquals(3, $response->viewData('certificates')->count());
    }

    public function test_kullanici_sertifika_olusturabilir()
    {
        $user = $this->loginAsUser();
        
        $certificateData = [
            'name' => 'AWS Certified Developer',
            'issuer' => 'Amazon Web Services',
            'date' => '2023-06-15',
            'description_tr' => 'AWS bulut geliştirici sertifikası',
            'description_en' => 'AWS cloud developer certification'
        ];
        
        $response = $this->post(route('certificates.store'), $certificateData);
        
        $response->assertRedirect(route('certificates.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında sertifika kaydının oluştuğunu kontrol et
        $this->assertDatabaseHas('certificates', [
            'name' => 'AWS Certified Developer',
            'user_id' => $user->id
        ]);
    }
    
    public function test_kullanici_kendi_sertifikalarini_duzenleyebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait bir sertifika oluştur
        $certificate = Certificate::factory()->create([
            'user_id' => $user->id,
            'name' => 'PHP Certification',
            'issuer' => 'Zend'
        ]);
        
        // Düzenleme formunu görüntüle
        $response = $this->get(route('certificates.edit', $certificate));
        $response->assertStatus(200);
        
        // Düzenleme işlemi
        $updatedData = [
            'name' => 'Zend PHP Certification',
            'issuer' => 'Zend Technologies',
            'date' => '2023-01-20',
            'description_tr' => 'Güncellenen açıklama',
            'description_en' => 'Updated description'
        ];
        
        $response = $this->put(route('certificates.update', $certificate), $updatedData);
        
        $response->assertRedirect(route('certificates.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında güncellendiğini kontrol et
        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'name' => 'Zend PHP Certification',
            'user_id' => $user->id
        ]);
    }
    
    public function test_kullanici_baskasinin_sertifikalarini_duzenleyemez()
    {
        $user = $this->loginAsUser();
        $otherUser = User::factory()->create();
        
        // Başka bir kullanıcıya ait sertifika oluştur
        $certificate = Certificate::factory()->create([
            'user_id' => $otherUser->id
        ]);
        
        // Düzenleme sayfasına erişim dene
        $response = $this->get(route('certificates.edit', $certificate));
        $response->assertStatus(403);
        
        // Düzenleme işlemi dene
        $updatedData = [
            'name' => 'Güncellenmiş Sertifika',
            'issuer' => 'Güncel Veren',
            'date' => '2024-01-01',
            'description_tr' => 'Güncellenmiş açıklama',
            'description_en' => 'Updated description'
        ];
        
        $response = $this->put(route('certificates.update', $certificate), $updatedData);
        $response->assertStatus(403);
        
        // Veritabanında değişmediğini kontrol et
        $this->assertDatabaseMissing('certificates', [
            'id' => $certificate->id,
            'name' => 'Güncellenmiş Sertifika'
        ]);
    }
    
    public function test_kullanici_kendi_sertifikalarini_silebilir()
    {
        $user = $this->loginAsUser();
        
        // Kullanıcıya ait bir sertifika oluştur
        $certificate = Certificate::factory()->create([
            'user_id' => $user->id
        ]);
        
        $response = $this->delete(route('certificates.destroy', $certificate));
        
        $response->assertRedirect(route('certificates.index'));
        $response->assertSessionHas('success');
        
        // Veritabanından silindiğini kontrol et
        $this->assertDatabaseMissing('certificates', [
            'id' => $certificate->id
        ]);
    }
    
    public function test_kullanici_baskasinin_sertifikalarini_silemez()
    {
        $user = $this->loginAsUser();
        $otherUser = User::factory()->create();
        
        // Başka bir kullanıcıya ait sertifika oluştur
        $certificate = Certificate::factory()->create([
            'user_id' => $otherUser->id
        ]);
        
        $response = $this->delete(route('certificates.destroy', $certificate));
        
        $response->assertStatus(403);
        
        // Veritabanından silinmediğini kontrol et
        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id
        ]);
    }
    
    public function test_admin_tum_sertifikalari_gorebilir()
    {
        $admin = $this->loginAsAdmin();
        
        // 2 normal kullanıcı oluştur ve her birine 2 sertifika ekle
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        Certificate::factory()->count(2)->create(['user_id' => $user1->id]);
        Certificate::factory()->count(2)->create(['user_id' => $user2->id]);
        
        $response = $this->get(route('certificates.index'));
        
        $response->assertStatus(200);
        
        // Admin tüm sertifikaları görebilmeli (4 tane)
        $this->assertEquals(4, $response->viewData('certificates')->count());
    }
    
    public function test_admin_baska_kullanicinin_sertifikalarini_duzenleyebilir()
    {
        $admin = $this->loginAsAdmin();
        $user = User::factory()->create();
        
        // Kullanıcıya ait bir sertifika oluştur
        $certificate = Certificate::factory()->create([
            'user_id' => $user->id
        ]);
        
        // Düzenleme sayfasına erişim
        $response = $this->get(route('certificates.edit', $certificate));
        $response->assertStatus(200);
        
        // Düzenleme işlemi
        $updatedData = [
            'name' => 'Admin Tarafından Güncellendi',
            'issuer' => 'Admin',
            'date' => '2024-06-01',
            'description_tr' => 'Admin tarafından güncellenen açıklama',
            'description_en' => 'Description updated by admin'
        ];
        
        $response = $this->put(route('certificates.update', $certificate), $updatedData);
        
        $response->assertRedirect(route('certificates.index'));
        $response->assertSessionHas('success');
        
        // Veritabanında güncellendiğini kontrol et
        $this->assertDatabaseHas('certificates', [
            'id' => $certificate->id,
            'name' => 'Admin Tarafından Güncellendi'
        ]);
    }
}
