<?php

namespace App\Http\Controllers;

use App\Http\Requests\VolunteeringRequest;
use App\Models\Volunteering;
use App\Services\VolunteeringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VolunteeringController extends Controller
{
    /**
     * @var VolunteeringService
     */
    protected $volunteeringService;

    /**
     * VolunteeringController constructor.
     *
     * @param VolunteeringService $volunteeringService
     */
    public function __construct(VolunteeringService $volunteeringService)
    {
        $this->volunteeringService = $volunteeringService;
        $this->middleware('auth');
        $this->authorizeResource(Volunteering::class, 'volunteering');
    }

    /**
     * Kullanıcının tüm gönüllü çalışmalarını listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $volunteerings = $this->volunteeringService->getUserVolunteerings(Auth::id());
        
        return view('volunteering.index', compact('volunteerings'));
    }

    /**
     * Yeni gönüllü çalışma oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('volunteering.create');
    }

    /**
     * Yeni gönüllü çalışma bilgisini kaydet
     *
     * @param VolunteeringRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(VolunteeringRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $volunteering = $this->volunteeringService->createVolunteering($data);
            
            Log::info('Volunteering created', ['user_id' => Auth::id(), 'volunteering_id' => $volunteering->id]);
            return redirect()->route('volunteerings.index')->with('success', __('app.volunteering_created'));
        } catch (\Exception $e) {
            Log::error('Error creating volunteering', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('volunteerings.index')->with('error', __('app.volunteering_create_error'));
        }
    }

    /**
     * Gönüllü çalışma düzenleme formunu göster
     *
     * @param Volunteering $volunteering
     * @return \Illuminate\View\View
     */
    public function edit(Volunteering $volunteering)
    {
        return view('volunteering.edit', compact('volunteering'));
    }

    /**
     * Gönüllü çalışma bilgisini güncelle
     *
     * @param VolunteeringRequest $request
     * @param Volunteering $volunteering
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(VolunteeringRequest $request, Volunteering $volunteering)
    {
        try {
            $this->volunteeringService->updateVolunteering($volunteering->id, $request->validated());
            
            Log::info('Volunteering updated', ['user_id' => Auth::id(), 'volunteering_id' => $volunteering->id]);
            return redirect()->route('volunteerings.index')->with('success', __('app.volunteering_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating volunteering', ['user_id' => Auth::id(), 'volunteering_id' => $volunteering->id, 'error' => $e->getMessage()]);
            return redirect()->route('volunteerings.index')->with('error', __('app.volunteering_update_error'));
        }
    }

    /**
     * Gönüllü çalışma bilgisini sil
     *
     * @param Volunteering $volunteering
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Volunteering $volunteering)
    {
        try {
            $this->volunteeringService->deleteVolunteering($volunteering->id);
            
            Log::info('Volunteering deleted', ['user_id' => Auth::id(), 'volunteering_id' => $volunteering->id]);
            return redirect()->route('volunteerings.index')->with('success', __('app.volunteering_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting volunteering', ['user_id' => Auth::id(), 'volunteering_id' => $volunteering->id, 'error' => $e->getMessage()]);
            return redirect()->route('volunteerings.index')->with('error', __('app.volunteering_delete_error'));
        }
    }
}
