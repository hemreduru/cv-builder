<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceRequest;
use App\Models\Experience;
use App\Services\ExperienceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class ExperienceController extends Controller
{
    /**
     * @var ExperienceService
     */
    protected $experienceService;

    /**
     * ExperienceController constructor.
     *
     * @param ExperienceService $experienceService
     */
    public function __construct(ExperienceService $experienceService)
    {
        $this->experienceService = $experienceService;
        $this->middleware('auth');
        $this->authorizeResource(Experience::class, 'experience');
    }

    /**
     * Tüm deneyimleri listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $experiences = $this->experienceService->getUserExperiences(Auth::id());
        
        return view('experience.index', compact('experiences'));
    }

    /**
     * Yeni deneyim oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('experience.create');
    }

    /**
     * Yeni deneyim kaydet
     *
     * @param ExperienceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ExperienceRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $experience = $this->experienceService->createExperience($data);
            
            Log::info("User ID: " . Auth::id() . " created experience ID: " . $experience->id);
            
            return redirect()->route('experiences.index')->with('success', __('app.experience_created'));
        } catch (Exception $e) {
            Log::error("Error creating experience by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', __('app.experience_create_error'));
        }
    }

    /**
     * Deneyim düzenleme formunu göster
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Experience $experience)
    {
        return view('experience.edit', compact('experience'));
    }

    /**
     * Deneyimi güncelle
     *
     * @param ExperienceRequest $request
     * @param Experience $experience
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ExperienceRequest $request, Experience $experience)
    {
        try {
            $this->experienceService->updateExperience($experience->id, $request->validated());
            
            Log::info("User ID: " . Auth::id() . " updated experience ID: " . $experience->id);
            
            return redirect()->route('experiences.index')->with('success', __('app.experience_updated'));
        } catch (Exception $e) {
            Log::error("Error updating experience ID " . $experience->id . " by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', __('app.experience_update_error'));
        }
    }

    /**
     * Deneyimi sil
     *
     * @param Experience $experience
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Experience $experience)
    {
        try {
            $this->experienceService->deleteExperience($experience->id);
            
            Log::info("User ID: " . Auth::id() . " deleted experience ID: " . $experience->id);
            
            return redirect()->route('experiences.index')->with('success', __('app.experience_deleted'));
        } catch (Exception $e) {
            Log::error("Error deleting experience ID " . $experience->id . " by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->with('error', __('app.experience_delete_error'));
        }
    }
}
