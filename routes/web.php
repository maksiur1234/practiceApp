<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\GiftController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Logowanie za pomocą Facebooka
Route::get('auth/{provider}/redirect', [\App\Http\Controllers\Auth\ProviderController::class, 'redirect']);
Route::get('auth/{provider}/callback', [\App\Http\Controllers\Auth\ProviderController::class, 'callback']);

// Obsługa logowania
// Uwierzytelnienie sesji
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Rejestracja
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest');

// Weryfikacja adresu e-mail
Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Resetowanie hasła
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Auth::routes();

Route::get('calendar-event', [CalenderController::class, 'index']);
Route::post('calendar-crud-ajax', [CalenderController::class, 'calendarEvents']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('company.details');
Route::post('/companies', [CompanyController::class, 'store'])->name('companies');
Route::get('/create-company', [CompanyController::class, 'create'])->name('companies.create');
Route::post('/store-company', [CompanyController::class, 'store'])->name('companies.store');

// Visit Request routes
//Route::match(['get', 'put'], '/visit-request/{eventId}', [CalenderController::class, 'sendVisitRequest'])
//    ->name('visit-request');
//

Route::get('/company/{companyId}/visit-requests', [CalenderController::class, 'showCompanyVisitRequests'])
    ->name('company.visit.requests');
Route::get('/visit-request/{eventId}', [CalenderController::class, 'showVisitRequestDetails'])
    ->name('visit.request.details');
Route::match(['get', 'post'], '/company/{companyId}/send-visit-request', [CalenderController::class, 'showVisitRequestForm'])
    ->name('send.visit.request');
Route::post('/company/{companyId}/send-visit-request', [CalenderController::class, 'sendVisitRequest'])
    ->name('send.visit.request');
Route::post('/visit-request/{eventId}/accept', [CalenderController::class, 'acceptVisitRequest'])
    ->name('visit.request.accept');
Route::delete('/visit-request/{eventId}/reject', [CalenderController::class, 'rejectVisitRequest'])
    ->name('visit.request.reject');
Route::post('/companies/{companyId}/upload-media', [CompanyController::class, 'uploadMedia'])
    ->name('upload.media');
Route::get('events/create', [EventController::class, 'create'])
    ->name('events.create');
Route::post('events/getCompaniesByType', [EventController::class, 'getCompaniesByType'])
    ->name('events.getCompaniesByType');
Route::post('events/store', [EventController::class, 'store'])
    ->name('events.store');
Route::get('/', [StripeController::class, 'index'])
    ->name('index');
Route::post('/checkout', [StripeController::class, 'checkout'])
    ->name('checkout');
Route::get('/success', [StripeController::class, 'success'])
    ->name('success');
Route::get('/presents/create', [GiftController::class, 'create'])
    ->name('presents.create');
Route::post('/presents', [GiftController::class, 'store'])
    ->name('presents.store');
Route::get('/presents/{id}', [GiftController::class, 'show'])
    ->name('presents.show');
Route::get('/presents/{id}/edit', [GiftController::class, 'edit'])
    ->name('presents.edit');
Route::put('/presents/{id}', [GiftController::class, 'update'])
    ->name('presents.update');
Route::delete('/presents/{id}', [GiftController::class, 'destroy'])
    ->name('presents.destroy');




