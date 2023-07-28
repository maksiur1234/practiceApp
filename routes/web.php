<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GiftController;
use Illuminate\Support\Facades\Auth;

// Logowanie za pomocą Facebooka
Route::get('auth/{provider}/redirect', [\App\Http\Controllers\Auth\ProviderController::class, 'redirect']);
Route::get('auth/{provider}/callback', [\App\Http\Controllers\Auth\ProviderController::class, 'callback']);

Route::middleware('guest')->group(function () {
    // Przekieruj na stronę React
    Route::get('/login', function () {
        return view('react-app');
    })->name('login');

    Route::get('/register', function () {
        return view('react-app');
    })->name('register');
});

Route::middleware('auth')->group(function () {
    // ... Twoje uwierzytelnione ścieżki
});



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/companies/{company}', [CompaniesController::class, 'show'])->name('company.details');
Route::post('/companies', [CompaniesController::class, 'store'])->name('companies');
Route::get('/create-company', [CompaniesController::class, 'create'])->name('companies.create');
Route::post('/store-company', [CompaniesController::class, 'store'])->name('companies.store');

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
Route::post('/companies/{companyId}/upload-media', [CompaniesController::class, 'uploadMedia'])
    ->name('upload.media');
Route::post('/selectCompanies', [EventController::class, 'selectCompanies'])
    ->name('events.selectCompanies');
Route::get('/chooseCompanyAndDate', [EventController::class, 'chooseCompanyAndDate'])
    ->name('events.chooseCompanyAndDate');
Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events/getCompaniesByType', [EventController::class, 'getCompaniesByType'])
    ->name('events.getCompaniesByType');
Route::post('/events/store', [EventController::class, 'store'])
    ->name('events.store');
Route::get('/events/chooseCompanyAndDate', [EventController::class, 'chooseCompanyAndDate'])
    ->name('events.chooseCompanyAndDate');
Route::post('/events/selectCompanies', [EventController::class, 'selectCompanies'])
    ->name('events.selectCompanies');
Route::put('/events/{id}', [EventController::class, 'update'])
    ->name('events.update');
Route::delete('/{id}', [EventController::class, 'destroy'])
    ->name('events.destroy');
Route::get('/', [StripeController::class, 'index'])
    ->name('index');
Route::post('/checkout', [StripeController::class, 'checkout'])
    ->name('checkout');
Route::get('/success', [StripeController::class, 'success'])
    ->name('success');
Route::get('/presents/chooseEvents', [GiftController::class, 'chooseEvents'])
    ->name('choose.events');
Route::get('/presents/create/{eventId}', [GiftController::class, 'create'])
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

