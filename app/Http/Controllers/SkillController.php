<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use App\Services\SkillService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SkillController extends Controller
{
    /**
     * @var SkillService
     */
    protected $skillService;

    /**
     * SkillController constructor.
     *
     * @param SkillService $skillService
     */
    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
        $this->middleware('auth');
        $this->authorizeResource(Skill::class, 'skill');
    }

    /**
     * Tüm yetenekleri listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $skills = $this->skillService->getUserSkills(Auth::id());
        $groupedSkills = $this->skillService->getUserSkillsByCategory(Auth::id());
        
        return view('skill.index', compact('skills', 'groupedSkills'));
    }

    /**
     * Yeni yetenek oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('skill.create');
    }

    /**
     * Yeni yetenek kaydet
     *
     * @param SkillRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SkillRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $skill = $this->skillService->createSkill($data);
            
            Log::info('Skill created', ['user_id' => Auth::id(), 'skill_id' => $skill->id]);
            return redirect()->route('skills.index')->with('success', __('app.skill_created'));
        } catch (\Exception $e) {
            Log::error('Error creating skill', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('skills.index')->with('error', __('app.skill_create_error'));
        }
    }

    /**
     * Yetenek düzenleme formunu göster
     *
     * @param Skill $skill
     * @return \Illuminate\View\View
     */
    public function edit(Skill $skill)
    {
        return view('skill.edit', compact('skill'));
    }

    /**
     * Yetenek bilgisini güncelle
     *
     * @param SkillRequest $request
     * @param Skill $skill
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SkillRequest $request, Skill $skill)
    {
        try {
            $this->skillService->updateSkill($skill->id, $request->validated());
            
            Log::info('Skill updated', ['user_id' => Auth::id(), 'skill_id' => $skill->id]);
            return redirect()->route('skills.index')->with('success', __('app.skill_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating skill', ['user_id' => Auth::id(), 'skill_id' => $skill->id, 'error' => $e->getMessage()]);
            return redirect()->route('skills.index')->with('error', __('app.skill_update_error'));
        }
    }

    /**
     * Yetenek bilgisini sil
     *
     * @param Skill $skill
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Skill $skill)
    {
        try {
            $this->skillService->deleteSkill($skill->id);
            
            Log::info('Skill deleted', ['user_id' => Auth::id(), 'skill_id' => $skill->id]);
            return redirect()->route('skills.index')->with('success', __('app.skill_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting skill', ['user_id' => Auth::id(), 'skill_id' => $skill->id, 'error' => $e->getMessage()]);
            return redirect()->route('skills.index')->with('error', __('app.skill_delete_error'));
        }
    }
}
