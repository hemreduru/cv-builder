<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PortfolioService;

class PortfolioController extends Controller
{
    protected $portfolioService;

    public function __construct(PortfolioService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    /**
     * Kullanıcının portfolyo sayfasını göster
     * 
     * @param string $username
     * @return \Illuminate\View\View
     */
    public function show($username)
    {
        $user = User::where('name', $username)->firstOrFail();
        
        $data = $this->portfolioService->getUserPortfolioData($user->id);
        
        return view('public.portfolio.show', compact('data', 'user'));
    }
}
