<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

// Rotas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/users', [UserController::class, 'store']); // cadastro público

// Rotas protegidas por JWT
Route::middleware('jwt.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD do usuário
    Route::get('/users', [UserController::class, 'show']); // ler dados do próprio usuário
    Route::patch('/users/{user_id}', [UserController::class, 'update']); // editar
    Route::delete('/users/{user_id}', [UserController::class, 'destroy']); // deletar
});
