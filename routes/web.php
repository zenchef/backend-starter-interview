<?php

use App\Http\Controllers\Web\ShowBookingPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', ShowBookingPageController::class);
