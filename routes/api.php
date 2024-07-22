<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/{user}', [UserController::class, 'show']);
    Route::post('/user/{user}/subscription', [UserController::class, 'addSubscription']);
    Route::put('/user/{user}/subscription/{subscription}', [UserController::class, 'updateSubscription']);
    Route::delete('/user/{user}/subscription/{subscription}', [UserController::class, 'deleteSubscription']);
    Route::post('/user/{user}/transaction', [UserController::class, 'addTransaction']);
});
