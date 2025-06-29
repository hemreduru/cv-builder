<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LocaleService
{
    /**
     * Switch application locale and store in session.
     */
    public function switch(string $locale): void
    {
        if (! in_array($locale, ['en', 'tr'])) {
            $locale = 'en';
        }

        Session::put('locale', $locale);
        App::setLocale($locale);
    }
}
