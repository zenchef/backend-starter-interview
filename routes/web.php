<?php

use App\Http\Controllers\Web\ShowBookingPageController;
use App\Http\Controllers\Web\StoreBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowBookingPageController::class);
Route::post('/bookings', StoreBookingController::class)->name('bookings.store');
