<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/courses', [CourseController::class, 'apiIndex']);
