<?php

use App\Http\Controllers\Api\Driver\DriverAuthController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\RidesController;
use App\Http\Controllers\Api\User\UserLocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['prefix' => 'user'], function () {
    // Public Routes
    Route::post('/signup', [AuthController::class, 'submitSignupDetails']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);

    Route::middleware('auth:sanctum')->group(function () {
        // Protected Routes
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/profile', [AuthController::class, 'userProfile']);

        // user Locations
        Route::get('/allLocations', [UserLocationController::class, 'index']);
        Route::post('/store-locations', [UserLocationController::class, 'store']);
        Route::get('/single-locations/{id}', [UserLocationController::class, 'show']);
        Route::put('/update-locations/{id}', [UserLocationController::class, 'update']);
        Route::delete('/destroy-locations/{id}', [UserLocationController::class, 'destroy']);

        //Ride
        Route::group(['prefix' => 'ride'], function () {
            Route::post('/search', [RidesController::class, 'searchRide']);
            Route::post('/book', [RidesController::class, 'bookRide']);
            Route::post('/offer-fare', [RidesController::class, 'offerFareRide']);
            Route::post('/cancel', [RidesController::class, 'cancelRide']);
            Route::get('/edit', [RidesController::class, 'editRide']);
        });
    });
});
Route::group(['prefix' => 'driver'], function () {
 
   Route::post('/signup', [DriverAuthController::class, 'submitSignupDetails']);

});