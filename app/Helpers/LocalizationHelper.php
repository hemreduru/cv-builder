<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class LocalizationHelper
{
    /**
     * Return localized field value with fallback handling.
     *
     * Usage: LocalizationHelper::get($model, 'title');
     */
    public static function get(Model $model, string $field): ?string
    {
        $locale = app()->getLocale();
        $fallback = config('app.fallback_locale', 'tr');

        $localizedColumn = $field . '_' . $locale;
        $fallbackColumn = $field . '_' . $fallback;

        if ($model->{$localizedColumn}) {
            return $model->{$localizedColumn};
        }

        return $model->{$fallbackColumn} ?? null;
    }
}
