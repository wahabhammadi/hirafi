<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CraftsmanDashboardController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\CraftsmanRegistrationController;
use App\Http\Controllers\CraftsmanProfileController;
use App\Http\Controllers\ClientRegistrationController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\AdminOffreController;
use App\Http\Controllers\CraftsmanOffreController;
use App\Http\Controllers\ClientOffreController;
use App\Http\Controllers\Craftsman\PortfolioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Registration Type Route
Route::get('/register/type', function () {
    return view('auth.register-type');
})->middleware('guest')->name('register.type');

// Client Registration Routes
Route::middleware('guest')->group(function () {
    Route::get('/client/register', [ClientRegistrationController::class, 'showRegistrationForm'])
         ->name('client.register');
    Route::post('/client/register', [ClientRegistrationController::class, 'register'])
         ->name('client.register.submit');
    // ... other client registration routes ...
});

// Craftsman Registration Routes
Route::middleware('guest')->group(function () {
    Route::get('/craftsman/register', [CraftsmanRegistrationController::class, 'showRegistrationForm'])
         ->name('craftsman.register');
    Route::post('/craftsman/verify-id', [CraftsmanRegistrationController::class, 'verifyIdNumber'])
         ->name('craftsman.verify-id');
    Route::get('/craftsman/verify-otp', [CraftsmanRegistrationController::class, 'showVerifyOtpForm'])
         ->name('craftsman.verify-otp');
    Route::post('/craftsman/verify-otp', [CraftsmanRegistrationController::class, 'verifyOtp'])
         ->name('craftsman.verify-otp');
    Route::get('/craftsman/create-password', [CraftsmanRegistrationController::class, 'showCreatePasswordForm'])
         ->name('craftsman.create-password');
    Route::post('/craftsman/create-password', [CraftsmanRegistrationController::class, 'createPassword'])
         ->name('craftsman.create-password');
});

// Craftsman Profile Completion Routes
Route::middleware('auth')->group(function () {
    Route::get('/craftsman/complete-profile', [CraftsmanRegistrationController::class, 'showCompleteProfileForm'])
         ->name('craftsman.complete-profile');
    Route::post('/craftsman/complete-profile', [CraftsmanRegistrationController::class, 'completeProfile'])
         ->name('craftsman.complete-profile');
    Route::get('/craftsman/edit-profile', [CraftsmanRegistrationController::class, 'showCompleteProfileForm'])
         ->name('craftsman.edit-profile');
    Route::post('/craftsman/edit-profile', [CraftsmanRegistrationController::class, 'completeProfile'])
         ->name('craftsman.edit-profile');
});

// Craftsman Dashboard Routes
Route::middleware(['auth', 'craftsman'])->group(function () {
    // Route::get('/craftsman/dashboard', [CraftsmanDashboardController::class, 'index'])
    //      ->name('craftsman.dashboard');
    Route::get('/craftsman/profile', [CraftsmanProfileController::class, 'show'])->name('craftsman.profile');
});

// مسارات التحقق من البريد الإلكتروني - تأكد من أنها خارج مجموعة guest middleware
Route::get('/verify-email', [App\Http\Controllers\Auth\EmailVerificationController::class, 'show'])
    ->name('verification.notice');
Route::post('/verify-email', [App\Http\Controllers\Auth\EmailVerificationController::class, 'verify'])
    ->name('verification.verify-code');
Route::get('/resend-verification-code', [App\Http\Controllers\Auth\EmailVerificationController::class, 'resend'])
    ->name('verification.resend');

// مسارات إدارة المشاريع (الأوامر)
Route::resource('commandes', CommandeController::class);

// مسارات العروض
Route::get('/commandes/{commande}/offres/create', [OffreController::class, 'create'])->name('offres.create')->middleware(['auth', 'verified', 'role:craftsman']);
Route::post('/commandes/{commande}/offres', [OffreController::class, 'store'])->name('offres.store')->middleware(['auth', 'verified', 'role:craftsman']);
Route::get('/offres/{offre}/edit', [OffreController::class, 'edit'])->name('offres.edit')->middleware(['auth', 'verified', 'role:craftsman']);
Route::put('/offres/{offre}', [OffreController::class, 'update'])->name('offres.update')->middleware(['auth', 'verified', 'role:craftsman']);
Route::delete('/offres/{offre}', [OffreController::class, 'destroy'])->name('offres.destroy')->middleware(['auth', 'verified', 'role:craftsman']);

// مسارات عرض العروض للحرفي
Route::middleware(['auth', 'verified', 'role:craftsman'])->group(function () {
    Route::get('/craftsman/offres', [CraftsmanOffreController::class, 'index'])->name('craftsman.offres.index');
    Route::get('/craftsman/offres/{offre}', [CraftsmanOffreController::class, 'show'])->name('craftsman.offres.show');
    Route::get('/craftsman/ratings', [CraftsmanOffreController::class, 'ratings'])->name('craftsman.ratings');
    
    // مسارات إدارة ملف الأعمال للحرفي (Portfolio)
    Route::resource('craftsman/portfolio', PortfolioController::class)->names([
        'index' => 'craftsman.portfolio.index',
        'create' => 'craftsman.portfolio.create',
        'store' => 'craftsman.portfolio.store',
        'show' => 'craftsman.portfolio.show',
        'edit' => 'craftsman.portfolio.edit',
        'update' => 'craftsman.portfolio.update',
        'destroy' => 'craftsman.portfolio.destroy',
    ])->except(['destroy']);
    Route::delete('/craftsman/portfolio/{work}', [PortfolioController::class, 'destroy'])->name('craftsman.portfolio.destroy');
});

// عرض ملف الأعمال للجميع
Route::get('/craftsman/{userId}/portfolio', [PortfolioController::class, 'publicPortfolio'])->name('craftsman.profile');

// مسارات عرض العروض الواردة للعميل
Route::middleware(['auth', 'verified', 'role:client'])->group(function () {
    Route::get('/client/offres', [ClientOffreController::class, 'index'])->name('client.offres.index');
    Route::get('/client/offres/{offre}', [ClientOffreController::class, 'show'])->name('client.offres.show');
    Route::put('/client/offres/{offre}/status', [ClientOffreController::class, 'updateStatus'])->name('client.offres.update-status');
    Route::post('/client/offres/{offre}/rate', [ClientOffreController::class, 'rateCraftsman'])->name('client.offres.rate');
    Route::delete('/client/offres/{offre}', [ClientOffreController::class, 'destroy'])->name('client.offres.destroy');
});

// مسارات إدارة العروض للمدير
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/offres', [AdminOffreController::class, 'index'])->name('admin.offres.index');
    Route::get('/admin/offres/{offre}', [AdminOffreController::class, 'show'])->name('admin.offres.show');
    Route::put('/admin/offres/{offre}/status', [AdminOffreController::class, 'updateStatus'])->name('admin.offres.update-status');
    Route::get('/admin/offres-export', [AdminOffreController::class, 'export'])->name('admin.offres.export');
});

require __DIR__.'/auth.php';
