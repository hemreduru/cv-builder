<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;

/**
 * Test rotalarını sağlayan provider trait
 */
trait TestRouteProvider
{
    /**
     * Test rotalarını ayarla
     */
    protected function defineRoutes()
    {
        // Skills rotaları
        Route::get('/skills', function () { return response()->json(['success' => true]); })->name('skills.index');
        Route::get('/skills/create', function () { return response()->json(['success' => true]); })->name('skills.create');
        Route::post('/skills', function () { return redirect()->route('skills.index'); })->name('skills.store');
        Route::get('/skills/{skill}/edit', function ($skill) { return response()->json(['success' => true, 'skill' => $skill]); })->name('skills.edit');
        Route::put('/skills/{skill}', function () { return redirect()->route('skills.index'); })->name('skills.update');
        Route::delete('/skills/{skill}', function () { return redirect()->route('skills.index'); })->name('skills.destroy');
        
        // Experience rotaları
        Route::get('/experiences', function () { return response()->json(['success' => true]); })->name('experiences.index');
        Route::get('/experiences/create', function () { return response()->json(['success' => true]); })->name('experiences.create');
        Route::post('/experiences', function () { return redirect()->route('experiences.index'); })->name('experiences.store');
        Route::get('/experiences/{experience}/edit', function ($experience) { return response()->json(['success' => true, 'experience' => $experience]); })->name('experiences.edit');
        Route::put('/experiences/{experience}', function () { return redirect()->route('experiences.index'); })->name('experiences.update');
        Route::get('/experiences/{experience}', function ($experience) { return response()->json(['success' => true, 'experience' => $experience]); })->name('experiences.show');
        Route::delete('/experiences/{experience}', function () { return redirect()->route('experiences.index'); })->name('experiences.destroy');
        
        // Languages rotaları
        Route::get('/languages', function () { return response()->json(['success' => true]); })->name('languages.index');
        Route::get('/languages/create', function () { return response()->json(['success' => true]); })->name('languages.create');
        Route::post('/languages', function () { return redirect()->route('languages.index'); })->name('languages.store');
        Route::get('/languages/{language}/edit', function ($language) { return response()->json(['success' => true, 'language' => $language]); })->name('languages.edit');
        Route::put('/languages/{language}', function () { return redirect()->route('languages.index'); })->name('languages.update');
        Route::delete('/languages/{language}', function () { return redirect()->route('languages.index'); })->name('languages.destroy');
        
        // Certificates rotaları
        Route::get('/certificates', function () { return response()->json(['success' => true]); })->name('certificates.index');
        Route::get('/certificates/create', function () { return response()->json(['success' => true]); })->name('certificates.create');
        Route::post('/certificates', function () { return redirect()->route('certificates.index'); })->name('certificates.store');
        Route::get('/certificates/{certificate}/edit', function ($certificate) { return response()->json(['success' => true, 'certificate' => $certificate]); })->name('certificates.edit');
        Route::put('/certificates/{certificate}', function () { return redirect()->route('certificates.index'); })->name('certificates.update');
        Route::delete('/certificates/{certificate}', function () { return redirect()->route('certificates.index'); })->name('certificates.destroy');
        
        // Awards rotaları
        Route::get('/awards', function () { return response()->json(['success' => true]); })->name('awards.index');
        Route::get('/awards/create', function () { return response()->json(['success' => true]); })->name('awards.create');
        Route::post('/awards', function () { return redirect()->route('awards.index'); })->name('awards.store');
        Route::get('/awards/{award}/edit', function ($award) { return response()->json(['success' => true, 'award' => $award]); })->name('awards.edit');
        Route::put('/awards/{award}', function () { return redirect()->route('awards.index'); })->name('awards.update');
        Route::delete('/awards/{award}', function () { return redirect()->route('awards.index'); })->name('awards.destroy');
        
        // References rotaları
        Route::get('/references', function () { return response()->json(['success' => true]); })->name('references.index');
        Route::get('/references/create', function () { return response()->json(['success' => true]); })->name('references.create');
        Route::post('/references', function () { return redirect()->route('references.index'); })->name('references.store');
        Route::get('/references/{reference}/edit', function ($reference) { return response()->json(['success' => true, 'reference' => $reference]); })->name('references.edit');
        Route::put('/references/{reference}', function () { return redirect()->route('references.index'); })->name('references.update');
        Route::delete('/references/{reference}', function () { return redirect()->route('references.index'); })->name('references.destroy');
        
        // Hobbies rotaları
        Route::get('/hobbies', function () { return response()->json(['success' => true]); })->name('hobbies.index');
        Route::get('/hobbies/create', function () { return response()->json(['success' => true]); })->name('hobbies.create');
        Route::post('/hobbies', function () { return redirect()->route('hobbies.index'); })->name('hobbies.store');
        Route::get('/hobbies/{hobby}/edit', function ($hobby) { return response()->json(['success' => true, 'hobby' => $hobby]); })->name('hobbies.edit');
        Route::put('/hobbies/{hobby}', function () { return redirect()->route('hobbies.index'); })->name('hobbies.update');
        Route::delete('/hobbies/{hobby}', function () { return redirect()->route('hobbies.index'); })->name('hobbies.destroy');
        
        // Volunteerings rotaları
        Route::get('/volunteerings', function () { return response()->json(['success' => true]); })->name('volunteerings.index');
        Route::get('/volunteerings/create', function () { return response()->json(['success' => true]); })->name('volunteerings.create');
        Route::post('/volunteerings', function () { return redirect()->route('volunteerings.index'); })->name('volunteerings.store');
        Route::get('/volunteerings/{volunteering}/edit', function ($volunteering) { return response()->json(['success' => true, 'volunteering' => $volunteering]); })->name('volunteerings.edit');
        Route::put('/volunteerings/{volunteering}', function () { return redirect()->route('volunteerings.index'); })->name('volunteerings.update');
        Route::delete('/volunteerings/{volunteering}', function () { return redirect()->route('volunteerings.index'); })->name('volunteerings.destroy');
        
        // Publications rotaları
        Route::get('/publications', function () { return response()->json(['success' => true]); })->name('publications.index');
        Route::get('/publications/create', function () { return response()->json(['success' => true]); })->name('publications.create');
        Route::post('/publications', function () { return redirect()->route('publications.index'); })->name('publications.store');
        Route::get('/publications/{publication}/edit', function ($publication) { return response()->json(['success' => true, 'publication' => $publication]); })->name('publications.edit');
        Route::put('/publications/{publication}', function () { return redirect()->route('publications.index'); })->name('publications.update');
        Route::delete('/publications/{publication}', function () { return redirect()->route('publications.index'); })->name('publications.destroy');
        
        // Social links rotaları
        Route::get('/social-links', function () { return response()->json(['success' => true]); })->name('social-links.index');
        Route::get('/social-links/create', function () { return response()->json(['success' => true]); })->name('social-links.create');
        Route::post('/social-links', function () { return redirect()->route('social-links.index'); })->name('social-links.store');
        Route::get('/social-links/{social_link}/edit', function ($social_link) { return response()->json(['success' => true, 'social_link' => $social_link]); })->name('social-links.edit');
        Route::put('/social-links/{social_link}', function () { return redirect()->route('social-links.index'); })->name('social-links.update');
        Route::delete('/social-links/{social_link}', function () { return redirect()->route('social-links.index'); })->name('social-links.destroy');
    }
}
