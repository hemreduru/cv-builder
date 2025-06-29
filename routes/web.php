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
use App\Http\Controllers\Public\CVController;
use App\Http\Controllers\Public\PortfolioController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Ana sayfa
Route::view('/', 'welcome_bootstrap');

// Dil değiştirme rotası
Route::post('/locale', [LocaleController::class, 'switch'])->name('locale.switch');

// Public rotalar - Kimlik doğrulama gerektirmez
Route::get('/portfolio/{username}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/cv/{username}', [CVController::class, 'show'])->name('cv.show');
Route::get('/cv/{username}/download', [CVController::class, 'download'])->name('cv.download');

// Kimlik doğrulama gerektiren rotalar
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    
    // Diğer kullanıcıları görüntüle (salt okunur)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    
    // Admin rotaları
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Admin kullanıcı yönetimi
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });
    
    // Profil rotaları
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/image', [ProfileController::class, 'updateImage'])->name('profile.update.image');
    
    // Diğer kullanıcı verileri için resource rotaları
    Route::resources([
        'experiences' => ExperienceController::class,
        'educations' => EducationController::class,
        'projects' => ProjectController::class,
        'skills' => SkillController::class,
        'languages' => LanguageController::class,
        'certificates' => CertificateController::class,
        'awards' => AwardController::class,
        'references' => ReferenceController::class,
        'hobbies' => HobbyController::class,
        'volunteerings' => VolunteeringController::class,
        'publications' => PublicationController::class,
        'social-links' => SocialLinkController::class,
    ]);
    
    // Özel proje rotası
    Route::post('projects/toggle-featured/{id}', [ProjectController::class, 'toggleFeatured'])->name('projects.toggle.featured');
});

require __DIR__.'/auth.php';
