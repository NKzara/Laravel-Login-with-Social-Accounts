<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\GoogleEmailController;
use App\Http\Controllers\GoogleTasksController;

Route::get('/', function () {
    return view('welcome');
});

//available for visitors
Route::get('/about', [InfoController::class, 'index'])->name('info.index');
Route::get('/contact-us', [InfoController::class, 'contactUs'])->name('info.contact-us');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google-auth');
Route::get('/auth/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//must be authenticated to visit these routes
Route::middleware('auth')->group(function () {
    Route::resource('books', BookController::class);
    
    // Google Services Routes
    Route::get('/calendar', [GoogleCalendarController::class, 'index'])->name('calendar.index');
    Route::get('/email', [GoogleEmailController::class, 'index'])->name('email.index');
    Route::get('/todos', [GoogleTasksController::class, 'index'])->name('todos.index');
});

require __DIR__.'/auth.php';