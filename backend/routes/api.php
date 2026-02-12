<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SampleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PasswordResetController;
use App\Http\Controllers\Api\MaterialController;
use App\Http\Controllers\Api\TopicController;
use App\Http\Controllers\Api\ExamController;

// System Info
Route::get('/version', function (Request $request) {
    return response()->json([
        'laravel_version' => app()->version(),
        'php_version' => phpversion(),
    ]);
});



Route::apiResource('/users', UserController::class);

// Sample Route
Route::get('/sample', [SampleController::class, 'index']);
//Auth Route
Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/me', [AuthController::class, 'me']);
Route::post('/logout', [AuthController::class, 'logout']);
// Forgot Password Route
Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);

Route::get('materials/{id}/download', [MaterialController::class, 'download']);
//Material route for test
// Route::get('/materials', [MaterialController::class, 'index']);
// Route::post('/materials', [MaterialController::class, 'store']);
// Route::get('materials/{id}', [MaterialController::class, 'show']);
// Route::delete('/materials/{id}/', [MaterialController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::middleware('auth:sanctum')->get('/materials/stats', [MaterialController::class, 'stats']);
    //Material route
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::get('materials/{id}', [MaterialController::class, 'show']);
    // allow updates
    Route::put('/materials/{id}', [MaterialController::class, 'update']);
    Route::patch('/materials/{id}', [MaterialController::class, 'update']);
    Route::delete('/materials/{id}/', [MaterialController::class, 'destroy']);

    // Topic route
    Route::apiResource('topics', TopicController::class);

    // Exam routes
    Route::post('/exams/{id}/submit', [ExamController::class, 'submit']);
    Route::apiResource('exams', ExamController::class);

});
