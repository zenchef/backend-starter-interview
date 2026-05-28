<?php

use App\Http\Controllers\Api\StoreBookingController;
use Illuminate\Support\Facades\Route;

Route::post('/api/bookings', StoreBookingController::class);
