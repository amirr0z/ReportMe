<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageReplyController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketReplyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProjectController;
use App\Http\Controllers\UserSupervisorController;
use App\Http\Controllers\WarningController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {

    Route::middleware('guest')->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::put('/update', 'update');
        Route::get('/', 'index');
    });
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('messages.message-replies', MessageReplyController::class)->shallow();
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('reports', ReportController::class);
    Route::apiResource('warnings', WarningController::class);
    Route::apiResource('tickets', TicketController::class);
    Route::apiResource('tickets.ticket-replies', TicketReplyController::class)->shallow();
    Route::apiResource('user-projects', UserProjectController::class);
    Route::apiResource('user-supervisors', UserSupervisorController::class);
});
