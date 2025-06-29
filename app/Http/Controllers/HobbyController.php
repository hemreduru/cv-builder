<?php

namespace App\Http\Controllers;

use App\Http\Requests\HobbyRequest;
use App\Models\Hobby;
use App\Services\HobbyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HobbyController extends Controller
{
    /**
     * @var HobbyService
     */
    protected $hobbyService;

    /**
     * HobbyController constructor.
     *
     * @param HobbyService $hobbyService
     */
    public function __construct(HobbyService $hobbyService)
    {
        $this->hobbyService = $hobbyService;
        $this->middleware('auth');
        $this->authorizeResource(Hobby::class, 'hobby');
    }

    /**
     * Kullanıcının tüm hobilerini listele
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $hobbies = $this->hobbyService->getUserHobbies(Auth::id());
        
        return view('hobby.index', compact('hobbies'));
    }

    /**
     * Yeni hobi oluşturma formunu göster
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('hobby.create');
    }

    /**
     * Yeni hobi bilgisini kaydet
     *
     * @param HobbyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(HobbyRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            
            $hobby = $this->hobbyService->createHobby($data);
            
            Log::info('Hobby created', ['user_id' => Auth::id(), 'hobby_id' => $hobby->id]);
            return redirect()->route('hobbies.index')->with('success', __('app.hobby_created'));
        } catch (\Exception $e) {
            Log::error('Error creating hobby', ['user_id' => Auth::id(), 'error' => $e->getMessage()]);
            return redirect()->route('hobbies.index')->with('error', __('app.hobby_create_error'));
        }
    }

    /**
     * Hobi düzenleme formunu göster
     *
     * @param Hobby $hobby
     * @return \Illuminate\View\View
     */
    public function edit(Hobby $hobby)
    {
        return view('hobby.edit', compact('hobby'));
    }

    /**
     * Hobi bilgisini güncelle
     *
     * @param HobbyRequest $request
     * @param Hobby $hobby
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(HobbyRequest $request, Hobby $hobby)
    {
        try {
            $this->hobbyService->updateHobby($hobby->id, $request->validated());
            
            Log::info('Hobby updated', ['user_id' => Auth::id(), 'hobby_id' => $hobby->id]);
            return redirect()->route('hobbies.index')->with('success', __('app.hobby_updated'));
        } catch (\Exception $e) {
            Log::error('Error updating hobby', ['user_id' => Auth::id(), 'hobby_id' => $hobby->id, 'error' => $e->getMessage()]);
            return redirect()->route('hobbies.index')->with('error', __('app.hobby_update_error'));
        }
    }

    /**
     * Hobi bilgisini sil
     *
     * @param Hobby $hobby
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Hobby $hobby)
    {
        try {
            $this->hobbyService->deleteHobby($hobby->id);
            
            Log::info('Hobby deleted', ['user_id' => Auth::id(), 'hobby_id' => $hobby->id]);
            return redirect()->route('hobbies.index')->with('success', __('app.hobby_deleted'));
        } catch (\Exception $e) {
            Log::error('Error deleting hobby', ['user_id' => Auth::id(), 'hobby_id' => $hobby->id, 'error' => $e->getMessage()]);
            return redirect()->route('hobbies.index')->with('error', __('app.hobby_delete_error'));
        }
    }
}
