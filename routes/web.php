<?php

use App\Http\Controllers\LockerController;
use App\Http\Controllers\DepositController;

Route::resource('lockers', LockerController::class);
Route::resource('deposits', DepositController::class);

Route::post('/pickup/validate', [DepositController::class, 'validatePickup'])
    ->name('pickup.validate');

