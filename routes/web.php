<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return Auth::check() ? redirect()->route('admin.dashboard') : redirect()->route('admin.login');
});

// Admin prefix
Route::get('/admin', function () {
    return Auth::check() ? redirect()->route('admin.dashboard') : redirect()->route('admin.login');
});

// Login routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login'); 
Route::post('/admin/login/submit', [AuthController::class, 'login'])->name('admin.login.submit');

// Protected admin routes using Laravel default middleware
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('dashboard', [AuthController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

   
});