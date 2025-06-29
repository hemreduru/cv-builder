<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    /**
     * @var ProjectService
     */
    protected $projectService;

    /**
     * ProjectController constructor.
     *
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
        $this->middleware('auth');
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Tüm projeleri listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $projects = $this->projectService->getUserProjects(Auth::id());
        
        return view('project.index', compact('projects'));
    }

    /**
     * Yeni proje oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('project.create');
    }

    /**
     * Yeni proje kaydet
     *
     * @param ProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProjectRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            // Resim varsa, proje servisinde işlenecek
            if ($request->hasFile('image_file')) {
                $data['image_file'] = $request->file('image_file');
            }
            
            $project = $this->projectService->createProject($data);
            
            Log::info("User ID: " . Auth::id() . " created project ID: " . $project->id);
            
            return redirect()->route('projects.index')->with('success', __('app.project_created'));
        } catch (Exception $e) {
            Log::error("Error creating project by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', __('app.project_create_error'));
        }
    }

    /**
     * Proje düzenleme formunu göster
     *
     * @param Project $project
     * @return \Illuminate\View\View
     */
    public function edit(Project $project)
    {
        return view('project.edit', compact('project'));
    }

    /**
     * Proje bilgisini güncelle
     *
     * @param ProjectRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProjectRequest $request, Project $project)
    {
        try {
            $data = $request->validated();
            
            // Resim varsa, proje servisinde işlenecek
            if ($request->hasFile('image_file')) {
                $data['image_file'] = $request->file('image_file');
            }
            
            $this->projectService->updateProject($project->id, $data);
            
            Log::info("User ID: " . Auth::id() . " updated project ID: " . $project->id);
            
            return redirect()->route('projects.index')->with('success', __('app.project_updated'));
        } catch (Exception $e) {
            Log::error("Error updating project ID " . $project->id . " by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', __('app.project_update_error'));
        }
    }

    /**
     * Projeyi sil
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $project)
    {
        try {
            $this->projectService->deleteProject($project->id);
            
            Log::info("User ID: " . Auth::id() . " deleted project ID: " . $project->id);
            
            return redirect()->route('projects.index')->with('success', __('app.project_deleted'));
        } catch (Exception $e) {
            Log::error("Error deleting project ID " . $project->id . " by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->with('error', __('app.project_delete_error'));
        }
    }
    
    /**
     * Projenin öne çıkarma durumunu değiştir
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFeatured(Project $project)
    {
        try {
            // toggleFeatured policy kontrolü
            $this->authorize('toggleFeatured', $project);
            
            $featured = !$project->is_featured;
            $this->projectService->toggleFeatured($project->id, $featured);
            
            Log::info("User ID: " . Auth::id() . " toggled featured status for project ID: " . $project->id);
            
            $message = $featured 
                ? 'Proje başarıyla öne çıkarıldı.' 
                : 'Proje öne çıkarılmış durumdan çıkarıldı.';
            
            return redirect()->route('projects.index')->with('success', $message);
        } catch (Exception $e) {
            Log::error("Error toggling featured status for project ID " . $project->id . " by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->with('error', __('app.project_toggle_error'));
        }
    }
}
