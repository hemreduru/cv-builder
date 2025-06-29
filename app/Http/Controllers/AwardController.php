<?php

namespace App\Http\Controllers;

use App\Http\Requests\AwardRequest;
use App\Models\Award;
use App\Services\AwardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AwardController extends Controller
{
    /**
     * @var AwardService
     */
    protected $awardService;

    /**
     * AwardController constructor.
     *
     * @param AwardService $awardService
     */
    public function __construct(AwardService $awardService)
    {
        $this->awardService = $awardService;
        $this->middleware('auth');
        $this->authorizeResource(Award::class, 'award');
    }

    /**
     * Kullanıcının tüm ödüllerini listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $awards = $this->awardService->getUserAwards(Auth::id());
        
        return view('award.index', compact('awards'));
    }

    /**
     * Yeni ödül oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('award.create');
    }

    /**
     * Yeni ödül bilgisini kaydet
     *
     * @param AwardRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AwardRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $award = $this->awardService->createAward($data);
            
            Log::info('Award created', ['user_id' => Auth::id(), 'award_id' => $award->id]);
            return redirect()->route('awards.index')->with('success', __('app.award_created'));
        } catch (\Exception $e) {
            Log::error('Error creating award', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('awards.index')->with('error', __('app.award_create_error'));
        }
    }

    /**
     * Ödül düzenleme formunu göster
     *
     * @param Award $award
     * @return \Illuminate\View\View
     */
    public function edit(Award $award)
    {
        return view('award.edit', compact('award'));
    }

    /**
     * Ödül bilgisini güncelle
     *
     * @param AwardRequest $request
     * @param Award $award
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AwardRequest $request, Award $award)
    {
        try {
            $this->awardService->updateAward($award->id, $request->validated());
            
            Log::info('Award updated', ['user_id' => Auth::id(), 'award_id' => $award->id]);
            return redirect()->route('awards.index')->with('success', __('app.award_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating award', ['user_id' => Auth::id(), 'award_id' => $award->id, 'error' => $e->getMessage()]);
            return redirect()->route('awards.index')->with('error', __('app.award_update_error'));
        }
    }

    /**
     * Ödül bilgisini sil
     *
     * @param Award $award
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Award $award)
    {
        try {
            $this->awardService->deleteAward($award->id);
            
            Log::info('Award deleted', ['user_id' => Auth::id(), 'award_id' => $award->id]);
            return redirect()->route('awards.index')->with('success', __('app.award_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting award', ['user_id' => Auth::id(), 'award_id' => $award->id, 'error' => $e->getMessage()]);
            return redirect()->route('awards.index')->with('error', __('app.award_delete_error'));
        }
    }
}
