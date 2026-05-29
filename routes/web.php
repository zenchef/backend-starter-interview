<?php

declare(strict_types=1);

use App\Http\Controllers\ShowBookingPageController;
use App\Http\Controllers\StoreBookingController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowBookingPageController::class);
Route::post('/bookings', StoreBookingController::class)->name('bookings.store');
