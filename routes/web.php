<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home — course grid + category filter
Route::get('/', [CourseController::class, 'index'])->name('courses.index');

// Start fetching
Route::post('/fetch', [CourseController::class, 'fetch'])->name('courses.fetch');

// View a single fetch-job log
Route::get('/jobs/{job}', [CourseController::class, 'showJob'])->name('jobs.show');
