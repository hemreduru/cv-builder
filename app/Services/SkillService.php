<?php

namespace App\Services;

use App\Models\Skill;
use Illuminate\Support\Facades\Auth;

class SkillService extends BaseService
{
    /**
     * SkillService constructor.
     *
     * @param Skill $skill
     */
    public function __construct(Skill $skill)
    {
        parent::__construct($skill);
    }

    /**
     * Yeni yetenek kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Skill
     */
    public function createSkill(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        return $this->create($data);
    }

    /**
     * Yetenek kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Skill|null
     */
    public function updateSkill(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        return $this->update($id, $data);
    }

    /**
     * Bir kullanıcıya ait yetenekleri kategorilere göre gruplandırarak getir
     *
     * @param int $userId
     * @return array
     */
    public function getUserSkillsByCategory(int $userId)
    {
        $skills = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('category')
            ->orderBy('level', 'DESC')
            ->get();
        
        $groupedSkills = [];
        
        foreach ($skills as $skill) {
            $groupedSkills[$skill->category][] = $skill;
        }
        
        return $groupedSkills;
    }
    
    /**
     * Bir kullanıcıya ait yetenekler listesi
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserSkills(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('category')
            ->orderBy('level', 'DESC');
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Yetenek kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteSkill(int $id)
    {
        return $this->delete($id);
    }
}
