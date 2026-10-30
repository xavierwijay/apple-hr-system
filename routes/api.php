<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::post('/auth/login', [AuthController::class, 'login'])
        ->middleware('throttle:api-login')
        ->name('auth.login');

    Route::middleware('auth:api')->group(function (): void {
        Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');
        Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('/auth/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');

        Route::apiResource('employees', EmployeeController::class)->only(['index', 'show']);

        Route::middleware('admin')->group(function (): void {
            Route::apiResource('employees', EmployeeController::class)->only(['store', 'update', 'destroy']);
        });
    });
});
