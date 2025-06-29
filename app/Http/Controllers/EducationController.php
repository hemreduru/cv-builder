<?php

namespace App\Http\Controllers;

use App\Http\Requests\EducationRequest;
use App\Models\Education;
use App\Services\EducationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class EducationController extends Controller
{
    /**
     * @var EducationService
     */
    protected $educationService;

    /**
     * EducationController constructor.
     *
     * @param EducationService $educationService
     */
    public function __construct(EducationService $educationService)
    {
        $this->educationService = $educationService;
        $this->middleware('auth');
        $this->authorizeResource(Education::class, 'education');
    }

    /**
     * Tüm eğitim bilgilerini listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $educations = $this->educationService->getUserEducations(Auth::id());
        
        return view('education.index', compact('educations'));
    }

    /**
     * Yeni eğitim bilgisi oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('education.create');
    }

    /**
     * Yeni eğitim bilgisi kaydet
     *
     * @param EducationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(EducationRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $education = $this->educationService->createEducation($data);
            
            Log::info("User ID: " . Auth::id() . " created education ID: " . $education->id);
            
            return redirect()->route('educations.index')->with('success', __('app.education_created'));
        } catch (Exception $e) {
            Log::error("Error creating education by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', __('app.education_create_error'));
        }
    }

    /**
     * Eğitim bilgisi düzenleme formunu göster
     *
     * @param Education $education
     * @return \Illuminate\View\View
     */
    public function edit(Education $education)
    {
        return view('education.edit', compact('education'));
    }

    /**
     * Eğitim bilgisini güncelle
     *
     * @param EducationRequest $request
     * @param Education $education
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EducationRequest $request, Education $education)
    {
        try {
            $this->educationService->updateEducation($education->id, $request->validated());
            
            Log::info("User ID: " . Auth::id() . " updated education ID: " . $education->id);
            
            return redirect()->route('educations.index')->with('success', __('app.education_updated'));
        } catch (Exception $e) {
            Log::error("Error updating education ID " . $education->id . " by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', __('app.education_update_error'));
        }
    }

    /**
     * Eğitim bilgisini sil
     *
     * @param Education $education
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Education $education)
    {
        try {
            $this->educationService->deleteEducation($education->id);
            
            Log::info("User ID: " . Auth::id() . " deleted education ID: " . $education->id);
            
            return redirect()->route('educations.index')->with('success', __('app.education_deleted'));
        } catch (Exception $e) {
            Log::error("Error deleting education ID " . $education->id . " by user ID " . Auth::id() . ": " . $e->getMessage());
            return redirect()->back()->with('error', __('app.education_delete_error'));
        }
    }
}
