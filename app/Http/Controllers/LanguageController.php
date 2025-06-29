<?php

namespace App\Http\Controllers;

use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use App\Services\LanguageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * @var LanguageService
     */
    protected $languageService;

    /**
     * LanguageController constructor.
     *
     * @param LanguageService $languageService
     */
    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
        $this->middleware('auth');
        $this->authorizeResource(Language::class, 'language');
    }

    /**
     * Kullanıcının tüm dillerini listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $languages = $this->languageService->getUserLanguages(Auth::id());
        
        return view('language.index', compact('languages'));
    }

    /**
     * Yeni dil oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('language.create');
    }

    /**
     * Yeni dil bilgisini kaydet
     *
     * @param LanguageRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LanguageRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $language = $this->languageService->createLanguage($data);
            
            Log::info('Language created', ['user_id' => Auth::id(), 'language_id' => $language->id]);
            return redirect()->route('languages.index')->with('success', __('app.language_created'));
        } catch (\Exception $e) {
            Log::error('Error creating language', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('languages.index')->with('error', __('app.language_create_error'));
        }
    }

    /**
     * Dil düzenleme formunu göster
     *
     * @param Language $language
     * @return \Illuminate\View\View
     */
    public function edit(Language $language)
    {
        return view('language.edit', compact('language'));
    }

    /**
     * Dil bilgisini güncelle
     *
     * @param LanguageRequest $request
     * @param Language $language
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LanguageRequest $request, Language $language)
    {
        try {
            $this->languageService->updateLanguage($language->id, $request->validated());
            
            Log::info('Language updated', ['user_id' => Auth::id(), 'language_id' => $language->id]);
            return redirect()->route('languages.index')->with('success', __('app.language_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating language', ['user_id' => Auth::id(), 'language_id' => $language->id, 'error' => $e->getMessage()]);
            return redirect()->route('languages.index')->with('error', __('app.language_update_error'));
        }
    }

    /**
     * Dil bilgisini sil
     *
     * @param Language $language
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Language $language)
    {
        try {
            $this->languageService->deleteLanguage($language->id);
            
            Log::info('Language deleted', ['user_id' => Auth::id(), 'language_id' => $language->id]);
            return redirect()->route('languages.index')->with('success', __('app.language_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting language', ['user_id' => Auth::id(), 'language_id' => $language->id, 'error' => $e->getMessage()]);
            return redirect()->route('languages.index')->with('error', __('app.language_delete_error'));
        }
    }
}
