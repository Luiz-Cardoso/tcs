<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aqui ficam as rotas da API do seu projeto.
| As rotas públicas (login, cadastro) ficam fora do middleware.
| As rotas que precisam de JWT ficam dentro do grupo jwt.auth.
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('/users', [UserController::class, 'store']);

    Route::middleware('jwt.auth')->get('/user', [UserController::class, 'show']);

    Route::middleware('jwt.auth')->patch('/users/{user_id}', [UserController::class, 'update']);

    Route::middleware('jwt.auth')->delete('/users/{user_id}', [UserController::class, 'destroy']);

    Route::middleware('role:user')->group(function () {
        Route::get('/user/profile', [UserController::class, 'profile']);
    });

    Route::middleware('role:company')->group(function () {
        Route::get('/company/profile', [CompanyController::class, 'profile']);
    });



});
