<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageSwitcher
{
    /**
     * Handle an incoming request and set locale.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale', $request->getPreferredLanguage(['en', 'tr']) ?? 'en');

        if (! in_array($locale, ['en', 'tr'])) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
