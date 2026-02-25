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
use App\Http\Controllers\Api\ExamResultController;
use App\Http\Controllers\Api\CertificateTemplateController;
use App\Http\Controllers\Api\ProfileController;

// System Info (Public)
Route::get('/version', function (Request $request) {
    return response()->json([
        'laravel_version' => app()->version(),
        'php_version' => phpversion(),
    ]);
});

// Sample Route (Public)
Route::get('/sample', [SampleController::class, 'index']);

// Auth Routes (Public)
Route::post('/login', [AuthController::class, 'login']);

// Forgot Password Routes (Public)
Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp']);

// Materials download (Public)
Route::get('materials/{id}/download', [MaterialController::class, 'download']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::post('/profile/avatar', [ProfileController::class, 'avatar']);
    Route::post('/profile/password', [ProfileController::class, 'changePassword']);

    // Users (Employees)
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus']);
    Route::apiResource('users', UserController::class);

    // Materials
    Route::get('/materials/stats', [MaterialController::class, 'stats']);
    Route::get('/materials', [MaterialController::class, 'index']);
    Route::post('/materials', [MaterialController::class, 'store']);
    Route::get('materials/{id}', [MaterialController::class, 'show']);
    Route::put('/materials/{id}', [MaterialController::class, 'update']);
    Route::patch('/materials/{id}', [MaterialController::class, 'update']);
    Route::delete('/materials/{id}', [MaterialController::class, 'destroy']);

    // Topics
    Route::apiResource('topics', TopicController::class);

    // Exams
    Route::post('/exams/{id}/submit', [ExamController::class, 'submit']);
    Route::apiResource('exams', ExamController::class);

    // Exam Results
    Route::get('/exam-results', [ExamResultController::class, 'index']);

    // Certificate Settings
    Route::get('/certificate-settings', [CertificateTemplateController::class, 'show']);
    Route::put('/certificate-settings', [CertificateTemplateController::class, 'update']);
});

