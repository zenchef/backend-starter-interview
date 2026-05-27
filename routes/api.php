<?php

use App\Http\Controllers\Reservation\StoreReservationController;
use App\Http\Controllers\Slot\IndexSlotController;
use Illuminate\Support\Facades\Route;

Route::get('/slots', IndexSlotController::class);
Route::post('/reservations', StoreReservationController::class);
