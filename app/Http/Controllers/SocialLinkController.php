<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialLinkRequest;
use App\Models\SocialLink;
use App\Services\SocialLinkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SocialLinkController extends Controller
{
    /**
     * @var SocialLinkService
     */
    protected $socialLinkService;

    /**
     * SocialLinkController constructor.
     *
     * @param SocialLinkService $socialLinkService
     */
    public function __construct(SocialLinkService $socialLinkService)
    {
        $this->socialLinkService = $socialLinkService;
        $this->middleware('auth');
        $this->authorizeResource(SocialLink::class, 'social_link');
    }

    /**
     * Kullanıcının tüm sosyal medya bağlantılarını listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $socialLinks = $this->socialLinkService->getUserSocialLinks(Auth::id());
        
        return view('social_link.index', compact('socialLinks'));
    }

    /**
     * Yeni sosyal medya bağlantısı oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('social_link.create');
    }

    /**
     * Yeni sosyal medya bağlantısı bilgisini kaydet
     *
     * @param SocialLinkRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SocialLinkRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $socialLink = $this->socialLinkService->createSocialLink($data);
            
            Log::info('Social link created', ['user_id' => Auth::id(), 'social_link_id' => $socialLink->id]);
            return redirect()->route('social-links.index')->with('success', __('app.social_link_created'));
        } catch (\Exception $e) {
            Log::error('Error creating social link', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('social-links.index')->with('error', __('app.social_link_create_error'));
        }
    }

    /**
     * Sosyal medya bağlantısı düzenleme formunu göster
     *
     * @param SocialLink $social_link
     * @return \Illuminate\View\View
     */
    public function edit(SocialLink $social_link)
    {
        return view('social_link.edit', compact('social_link'));
    }

    /**
     * Sosyal medya bağlantısı bilgisini güncelle
     *
     * @param SocialLinkRequest $request
     * @param SocialLink $social_link
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SocialLinkRequest $request, SocialLink $social_link)
    {
        try {
            $this->socialLinkService->updateSocialLink($social_link->id, $request->validated());
            
            Log::info('Social link updated', ['user_id' => Auth::id(), 'social_link_id' => $social_link->id]);
            return redirect()->route('social-links.index')->with('success', __('app.social_link_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating social link', ['user_id' => Auth::id(), 'social_link_id' => $social_link->id, 'error' => $e->getMessage()]);
            return redirect()->route('social-links.index')->with('error', __('app.social_link_update_error'));
        }
    }

    /**
     * Sosyal medya bağlantısı bilgisini sil
     *
     * @param SocialLink $social_link
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SocialLink $social_link)
    {
        try {
            $this->socialLinkService->deleteSocialLink($social_link->id);
            
            Log::info('Social link deleted', ['user_id' => Auth::id(), 'social_link_id' => $social_link->id]);
            return redirect()->route('social-links.index')->with('success', __('app.social_link_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting social link', ['user_id' => Auth::id(), 'social_link_id' => $social_link->id, 'error' => $e->getMessage()]);
            return redirect()->route('social-links.index')->with('error', __('app.social_link_delete_error'));
        }
    }
}
