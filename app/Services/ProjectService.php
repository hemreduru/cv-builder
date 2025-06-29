<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectService extends BaseService
{
    /**
     * ProjectService constructor.
     *
     * @param Project $project
     */
    public function __construct(Project $project)
    {
        parent::__construct($project);
    }

    /**
     * Yeni proje kaydı oluştur
     *
     * @param array $data
     * @return \App\Models\Project
     */
    public function createProject(array $data)
    {
        // Auth kullanıcısı varsa created_by ve updated_by alanlarını ekleyelim
        if (Auth::check()) {
            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();
        }
        
        // Resim varsa yükleyelim
        if (isset($data['image_file']) && $data['image_file']) {
            $data['image'] = $this->uploadProjectImage($data['image_file']);
            unset($data['image_file']);
        }
        
        return $this->create($data);
    }

    /**
     * Proje kaydını güncelle
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Project|null
     */
    public function updateProject(int $id, array $data)
    {
        // Auth kullanıcısı varsa updated_by alanını ekleyelim
        if (Auth::check()) {
            $data['updated_by'] = Auth::id();
        }
        
        $project = $this->getById($id);
        
        if (!$project) {
            return null;
        }
        
        // Resim varsa yükleyelim ve eski resmi silelim
        if (isset($data['image_file']) && $data['image_file']) {
            // Eski resim varsa silelim
            if ($project->image && Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
            }
            
            $data['image'] = $this->uploadProjectImage($data['image_file']);
            unset($data['image_file']);
        }
        
        $project->update($data);
        return $project->fresh();
    }

    /**
     * Proje resmini yükle
     *
     * @param mixed $imageFile
     * @return string|null Resim yolu
     */
    private function uploadProjectImage($imageFile)
    {
        if (!$imageFile) {
            return null;
        }
        
        // Resmin benzersiz bir isim almasını sağlayalım
        $filename = time() . '_' . Auth::id() . '.' . $imageFile->getClientOriginalExtension();
        $path = $imageFile->storeAs('projects', $filename, 'public');
        
        return $path;
    }

    /**
     * Öne çıkan projeleri getir
     *
     * @param int $userId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedProjects(int $userId, int $limit = 5)
    {
        return $this->model->newQuery()
            ->where('user_id', $userId)
            ->where('is_featured', true)
            ->limit($limit)
            ->get();
    }
    
    /**
     * Proje öne çıkarma durumunu değiştir
     *
     * @param int $id
     * @param bool $featured
     * @return \App\Models\Project|null
     */
    public function toggleFeatured(int $id, bool $featured)
    {
        $project = $this->getById($id);
        
        if ($project) {
            $project->update([
                'is_featured' => $featured,
                'updated_by' => Auth::id()
            ]);
            
            return $project->fresh();
        }
        
        return null;
    }
    
    /**
     * Bir kullanıcıya ait projeler listesi
     *
     * @param int $userId
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserProjects(int $userId, array $relations = [])
    {
        $query = $this->model->newQuery()
            ->where('user_id', $userId)
            ->orderBy('is_featured', 'DESC')
            ->orderBy('created_at', 'DESC');
        
        if (!empty($relations)) {
            $query->with($relations);
        }
        
        return $query->get();
    }
    
    /**
     * Proje kaydını sil
     *
     * @param int $id
     * @return bool
     */
    public function deleteProject(int $id)
    {
        $project = $this->getById($id);
        
        if ($project && $project->image) {
            // Resim varsa silelim
            if (Storage::disk('public')->exists($project->image)) {
                Storage::disk('public')->delete($project->image);
            }
        }
        
        return $this->delete($id);
    }
}
