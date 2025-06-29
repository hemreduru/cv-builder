<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\CVService;
use Illuminate\Http\Response;

class CVController extends Controller
{
    protected $cvService;

    public function __construct(CVService $cvService)
    {
        $this->cvService = $cvService;
    }

    /**
     * Kullanıcının CV sayfasını göster
     * 
     * @param string $username
     * @return \Illuminate\View\View
     */
    public function show($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        $locale = app()->getLocale(); // Şu anki dil (tr veya en)
        
        $data = $this->cvService->getUserCVData($user->id, $locale);
        
        return view('public.cv.show', compact('data', 'user'));
    }
    
    /**
     * Kullanıcının CV'sini PDF olarak indir
     * 
     * @param string $username
     * @return \Illuminate\Http\Response
     */
    public function download($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        $locale = app()->getLocale(); // Şu anki dil (tr veya en)
        
        $pdf = $this->cvService->generateUserCVPdf($user->id, $locale);
        
        $fileName = $user->name . '_' . $user->surname . '_CV_' . strtoupper($locale) . '.pdf';
        
        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf;
            },
            $fileName,
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }
}
