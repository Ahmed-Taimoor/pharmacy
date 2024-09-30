<?php

use App\Mail\CredentialEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/register/pharmacy', [AuthController::class, 'registerPharmacy']);
    Route::post('/register/serviceprovider', [AuthController::class, 'registerServiceProvider']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

