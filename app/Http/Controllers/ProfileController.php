<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * @var ProfileService
     */
    protected $profileService;

    /**
     * ProfileController constructor.
     *
     * @param ProfileService $profileService
     */
    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
        $this->middleware('auth');
    }

    /**
     * Profil düzenleme formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $profile = $this->profileService->findOrCreateForUser(Auth::id());
        
        return view('profile.edit', compact('profile'));
    }

    /**
     * Profil bilgilerini güncelle
     *
     * @param ProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        $this->profileService->updateProfile(Auth::id(), $request->validated());
        
        return redirect()->route('profile.edit')->with('success', __('Profile updated successfully.'));
    }

    /**
     * Profil resmini güncelle
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);
        
        $this->profileService->updateProfileImage(Auth::id(), $request->file('image'));
        
        return redirect()->route('profile.edit')->with('success', __('Profile image updated successfully.'));
    }
}
