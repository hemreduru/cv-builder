<?php

namespace Tests\Feature;

use App\Models\Award;
use App\Models\Certificate;
use App\Models\Hobby;
use App\Models\Language;
use App\Models\Publication;
use App\Models\Reference;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use App\Models\Volunteering;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PolicyAuthorizationTest extends TestCase
{
    use WithFaker;

    /**
     * Test policy authorization for all resources
     *
     * @return void
     */
    public function test_kullanici_sadece_kendi_verilerine_erisebilir()
    {
        // Normal kullanıcı olarak giriş yap
        $user = $this->loginAsUser();
        
        // Başka bir kullanıcı oluştur
        $otherUser = User::factory()->create();
        
        // Başka kullanıcıya ait veriler oluştur
        $skill = Skill::factory()->create(['user_id' => $otherUser->id]);
        $language = Language::factory()->create(['user_id' => $otherUser->id]);
        $certificate = Certificate::factory()->create(['user_id' => $otherUser->id]);
        $award = Award::factory()->create(['user_id' => $otherUser->id]);
        $reference = Reference::factory()->create(['user_id' => $otherUser->id]);
        $hobby = Hobby::factory()->create(['user_id' => $otherUser->id]);
        $volunteering = Volunteering::factory()->create(['user_id' => $otherUser->id]);
        $publication = Publication::factory()->create(['user_id' => $otherUser->id]);
        $socialLink = SocialLink::factory()->create(['user_id' => $otherUser->id]);
        
        // Erişim denemeleri - tümü 403 döndürmeli
        $this->get(route('skills.edit', $skill))->assertStatus(403);
        $this->get(route('languages.edit', $language))->assertStatus(403);
        $this->get(route('certificates.edit', $certificate))->assertStatus(403);
        $this->get(route('awards.edit', $award))->assertStatus(403);
        $this->get(route('references.edit', $reference))->assertStatus(403);
        $this->get(route('hobbies.edit', $hobby))->assertStatus(403);
        $this->get(route('volunteerings.edit', $volunteering))->assertStatus(403);
        $this->get(route('publications.edit', $publication))->assertStatus(403);
        $this->get(route('social-links.edit', $socialLink))->assertStatus(403);
        
        // Silme denemeleri - tümü 403 döndürmeli
        $this->delete(route('skills.destroy', $skill))->assertStatus(403);
        $this->delete(route('languages.destroy', $language))->assertStatus(403);
        $this->delete(route('certificates.destroy', $certificate))->assertStatus(403);
        $this->delete(route('awards.destroy', $award))->assertStatus(403);
        $this->delete(route('references.destroy', $reference))->assertStatus(403);
        $this->delete(route('hobbies.destroy', $hobby))->assertStatus(403);
        $this->delete(route('volunteerings.destroy', $volunteering))->assertStatus(403);
        $this->delete(route('publications.destroy', $publication))->assertStatus(403);
        $this->delete(route('social-links.destroy', $socialLink))->assertStatus(403);
    }
    
    /**
     * Test admin can access all data
     *
     * @return void
     */
    public function test_admin_tum_verilere_erisebilir()
    {
        // Admin olarak giriş yap
        $admin = $this->loginAsAdmin();
        
        // Normal kullanıcı oluştur
        $user = User::factory()->create();
        
        // Kullanıcıya ait veriler oluştur
        $skill = Skill::factory()->create(['user_id' => $user->id]);
        $language = Language::factory()->create(['user_id' => $user->id]);
        $certificate = Certificate::factory()->create(['user_id' => $user->id]);
        $award = Award::factory()->create(['user_id' => $user->id]);
        $reference = Reference::factory()->create(['user_id' => $user->id]);
        $hobby = Hobby::factory()->create(['user_id' => $user->id]);
        $volunteering = Volunteering::factory()->create(['user_id' => $user->id]);
        $publication = Publication::factory()->create(['user_id' => $user->id]);
        $socialLink = SocialLink::factory()->create(['user_id' => $user->id]);
        
        // Erişim denemeleri - tümü 200 döndürmeli
        $this->get(route('skills.edit', $skill))->assertStatus(200);
        $this->get(route('languages.edit', $language))->assertStatus(200);
        $this->get(route('certificates.edit', $certificate))->assertStatus(200);
        $this->get(route('awards.edit', $award))->assertStatus(200);
        $this->get(route('references.edit', $reference))->assertStatus(200);
        $this->get(route('hobbies.edit', $hobby))->assertStatus(200);
        $this->get(route('volunteerings.edit', $volunteering))->assertStatus(200);
        $this->get(route('publications.edit', $publication))->assertStatus(200);
        $this->get(route('social-links.edit', $socialLink))->assertStatus(200);
        
        // Bir veriyi güncelleyerek işlem yapabildiğini kontrol et
        $response = $this->put(route('skills.update', $skill), [
            'name_tr' => 'Admin Tarafından Güncellenen Yetenek',
            'name_en' => 'Skill Updated By Admin',
            'level' => 80,
            'category' => 'Test'
        ]);
        
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'name_tr' => 'Admin Tarafından Güncellenen Yetenek'
        ]);
    }
    
    /**
     * Test before method in policy classes
     *
     * @return void
     */
    public function test_policy_before_metodu_admin_icin_calisir()
    {
        // Admin olarak giriş yap
        $admin = $this->loginAsAdmin();
        
        // 3 farklı kullanıcı oluştur
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        
        // Her bir kullanıcı için 2 yetenek oluştur (toplam 6)
        Skill::factory()->count(2)->create(['user_id' => $user1->id]);
        Skill::factory()->count(2)->create(['user_id' => $user2->id]);
        Skill::factory()->count(2)->create(['user_id' => $user3->id]);
        
        // Admin olarak index sayfasına git
        $response = $this->get(route('skills.index'));
        
        // Admin tüm yetenekleri görebilir (6 tane)
        $response->assertStatus(200);
        $this->assertEquals(6, $response->viewData('skills')->count());
    }
}
