<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocaleSwitchRequest;
use App\Services\LocaleService;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function __construct(private readonly LocaleService $localeService)
    {
    }

    public function switch(LocaleSwitchRequest $request): RedirectResponse
    {
        $this->localeService->switch($request->validated('locale'));

        return back();
    }
}
