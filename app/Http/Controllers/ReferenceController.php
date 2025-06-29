<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferenceRequest;
use App\Models\Reference;
use App\Services\ReferenceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReferenceController extends Controller
{
    /**
     * @var ReferenceService
     */
    protected $referenceService;

    /**
     * ReferenceController constructor.
     *
     * @param ReferenceService $referenceService
     */
    public function __construct(ReferenceService $referenceService)
    {
        $this->referenceService = $referenceService;
        $this->middleware('auth');
        $this->authorizeResource(Reference::class, 'reference');
    }

    /**
     * Kullanıcının tüm referanslarını listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $references = $this->referenceService->getUserReferences(Auth::id());
        
        return view('reference.index', compact('references'));
    }

    /**
     * Yeni referans oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('reference.create');
    }

    /**
     * Yeni referans bilgisini kaydet
     *
     * @param ReferenceRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReferenceRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $reference = $this->referenceService->createReference($data);
            
            Log::info('Reference created', ['user_id' => Auth::id(), 'reference_id' => $reference->id]);
            return redirect()->route('references.index')->with('success', __('app.reference_created'));
        } catch (\Exception $e) {
            Log::error('Error creating reference', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('references.index')->with('error', __('app.reference_create_error'));
        }
    }

    /**
     * Referans düzenleme formunu göster
     *
     * @param Reference $reference
     * @return \Illuminate\View\View
     */
    public function edit(Reference $reference)
    {
        return view('reference.edit', compact('reference'));
    }

    /**
     * Referans bilgisini güncelle
     *
     * @param ReferenceRequest $request
     * @param Reference $reference
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReferenceRequest $request, Reference $reference)
    {
        try {
            $this->referenceService->updateReference($reference->id, $request->validated());
            
            Log::info('Reference updated', ['user_id' => Auth::id(), 'reference_id' => $reference->id]);
            return redirect()->route('references.index')->with('success', __('app.reference_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating reference', ['user_id' => Auth::id(), 'reference_id' => $reference->id, 'error' => $e->getMessage()]);
            return redirect()->route('references.index')->with('error', __('app.reference_update_error'));
        }
    }

    /**
     * Referans bilgisini sil
     *
     * @param Reference $reference
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reference $reference)
    {
        try {
            $this->referenceService->deleteReference($reference->id);
            
            Log::info('Reference deleted', ['user_id' => Auth::id(), 'reference_id' => $reference->id]);
            return redirect()->route('references.index')->with('success', __('app.reference_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting reference', ['user_id' => Auth::id(), 'reference_id' => $reference->id, 'error' => $e->getMessage()]);
            return redirect()->route('references.index')->with('error', __('app.reference_delete_error'));
        }
    }
}
