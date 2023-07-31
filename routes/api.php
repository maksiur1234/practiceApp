<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CalenderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\GiftController;
use Illuminate\Support\Facades\Auth;

Route::group(['prefix' => 'api'], function () {
    Route::get('/types', [CompaniesController::class, 'create']);
    Route::post('/companies', [CompaniesController::class, 'store']);
    Route::get('/companies', [CompaniesController::class, 'index']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/register', [RegisterController::class, 'registerUser']);
Route::post('/login', [LoginController::class, 'login']);


