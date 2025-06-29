<?php

use App\Http\Controllers\AwardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\HobbyController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SocialLinkController;
use App\Http\Controllers\VolunteeringController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome_bootstrap');

Route::post('/locale', [LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.update.image');
    
    // Experience routes
    Route::resource('experiences', ExperienceController::class);
    
    // Education routes
    Route::resource('educations', EducationController::class);
    
    // Project routes
    Route::resource('projects', ProjectController::class);
    Route::post('projects/toggle-featured/{id}', [ProjectController::class, 'toggleFeatured'])->name('projects.toggle.featured');
    
    // Skill routes
    Route::resource('skills', SkillController::class);
    
    // Language routes
    Route::resource('languages', LanguageController::class);
    
    // Certificate routes
    Route::resource('certificates', CertificateController::class);
    
    // Award routes
    Route::resource('awards', AwardController::class);
    
    // Reference routes
    Route::resource('references', ReferenceController::class);
    
    // Hobby routes
    Route::resource('hobbies', HobbyController::class);
    
    // Volunteering routes
    Route::resource('volunteerings', VolunteeringController::class);
    
    // Publication routes
    Route::resource('publications', PublicationController::class);
    
    // Social Link routes
    Route::resource('social-links', SocialLinkController::class);
    
    // Dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Portfolio public route
Route::get('/portfolio/{username}', [App\Http\Controllers\PortfolioController::class, 'show'])
    ->name('portfolio.show');

require __DIR__.'/auth.php';
