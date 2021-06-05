<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\AuthController;
use App\Http\Controllers\Authentication\SystemController;

Route::apiResource('systems', SystemController::class)->only(['show']);

// Auth
Route::prefix('auth')->group(function () {
    Route::get('incorrect-password/{username}', [AuthController::class, 'incorrectPassword']);
    Route::post('password-forgot', [AuthController::class, 'passwordForgot']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('user-locked', [AuthController::class, 'userLocked']);
    Route::post('unlock-user', [AuthController::class, 'unlockUser']);
    Route::post('email-verified', [AuthController::class, 'emailVerified']);
    Route::get('verify-email/{user}', [AuthController::class, 'verifyEmail']);
    Route::post('register-socialite-user', [AuthController::class, 'registerSocialiteUser']);
});
Route::get('test/{institution}', function (\App\Models\App\Institution $institution) {
//                $request->user()->sendEmailVerificationNotification();
    return $institution;
});
