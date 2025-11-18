<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\DepositController;

// Locker CRUD
Route::resource('lockers', LockerController::class);

// Deposit CRUD
Route::resource('deposits', DepositController::class);

// Pickup form
Route::get('/pickup', function () {
    return view('pickup');
})->name('pickup.form');

// Validate pickup PIN
Route::post('/pickup/validate', [DepositController::class, 'validatePickup'])
    ->name('pickup.validate');
