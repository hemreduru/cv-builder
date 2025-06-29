<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicationRequest;
use App\Models\Publication;
use App\Services\PublicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PublicationController extends Controller
{
    /**
     * @var PublicationService
     */
    protected $publicationService;

    /**
     * PublicationController constructor.
     *
     * @param PublicationService $publicationService
     */
    public function __construct(PublicationService $publicationService)
    {
        $this->publicationService = $publicationService;
        $this->middleware('auth');
        $this->authorizeResource(Publication::class, 'publication');
    }

    /**
     * Kullanıcının tüm yayınlarını listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $publications = $this->publicationService->getUserPublications(Auth::id());
        
        return view('publication.index', compact('publications'));
    }

    /**
     * Yeni yayın oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('publication.create');
    }

    /**
     * Yeni yayın bilgisini kaydet
     *
     * @param PublicationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PublicationRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $publication = $this->publicationService->createPublication($data);
            
            Log::info('Publication created', ['user_id' => Auth::id(), 'publication_id' => $publication->id]);
            return redirect()->route('publications.index')->with('success', __('app.publication_created'));
        } catch (\Exception $e) {
            Log::error('Error creating publication', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('publications.index')->with('error', __('app.publication_create_error'));
        }
    }

    /**
     * Yayın düzenleme formunu göster
     *
     * @param Publication $publication
     * @return \Illuminate\View\View
     */
    public function edit(Publication $publication)
    {
        return view('publication.edit', compact('publication'));
    }

    /**
     * Yayın bilgisini güncelle
     *
     * @param PublicationRequest $request
     * @param Publication $publication
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PublicationRequest $request, Publication $publication)
    {
        try {
            $this->publicationService->updatePublication($publication->id, $request->validated());
            
            Log::info('Publication updated', ['user_id' => Auth::id(), 'publication_id' => $publication->id]);
            return redirect()->route('publications.index')->with('success', __('app.publication_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating publication', ['user_id' => Auth::id(), 'publication_id' => $publication->id, 'error' => $e->getMessage()]);
            return redirect()->route('publications.index')->with('error', __('app.publication_update_error'));
        }
    }

    /**
     * Yayın bilgisini sil
     *
     * @param Publication $publication
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Publication $publication)
    {
        try {
            $this->publicationService->deletePublication($publication->id);
            
            Log::info('Publication deleted', ['user_id' => Auth::id(), 'publication_id' => $publication->id]);
            return redirect()->route('publications.index')->with('success', __('app.publication_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting publication', ['user_id' => Auth::id(), 'publication_id' => $publication->id, 'error' => $e->getMessage()]);
            return redirect()->route('publications.index')->with('error', __('app.publication_delete_error'));
        }
    }
}
