<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\EnsureTokenIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware([EnsureTokenIsValid::class])->group(function($router){
    Route::get('/tasks', [TaskController::class, 'list']);
    Route::post('/tasks', [TaskController::class, 'create']);
    Route::put('/tasks/{id}', [TaskController::class, 'edit']);
    Route::delete('/tasks/{id}', [TaskController::class, 'remove']);
});
