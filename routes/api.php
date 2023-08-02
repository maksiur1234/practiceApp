<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\Auth\RegisterController;



//Route::post('events/getCompaniesByType', [EventController::class, 'getCompaniesByType'])
//    ->name('events.getCompaniesByType');
//
//// Ścieżki dla zasobów API
//Route::prefix('events')->group(function () {
//    Route::post('store', [EventController::class, 'store'])->name('events.store');
//});

Route::post('/register', [RegisterController::class, 'registerUser']);
Route::get('companies', [CompaniesController::class, 'index']);
