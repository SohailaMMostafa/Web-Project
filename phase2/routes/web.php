<?php

use App\Http\Controllers\ActorController;
use App\Http\Controllers\CheckUsernameController;
use App\Http\Controllers\EmailValidationController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('registeration');
});

Route::post('/check-username', [CheckUsernameController::class, 'checkUsername'])
    ->name('check.username');

Route::post('/check-email', [EmailValidationController::class, 'checkEmailExistence'])
    ->name('check.email');

Route::post('/register', [RegistrationController::class, 'register'])
    ->name('register');

Route::get('/get-born-today/{date_of_birth}', [ActorController::class,'getActorsByBirthdate']);

Route::get('/locale/{lang}', [LocaleController::class,'setLocale']);
