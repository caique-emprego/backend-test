<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HealthCheckController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Healthcheck
Route::get('healthcheck', [HealthCheckController::class, 'healthCheck']);

// Users (Rota Pública)
Route::prefix('users')->group(function () {
    // Rota 1
    Route::post('register', [UserController::class, 'register']);

    // Rota 2: Login com autenticação básica, deve somente validar o client
    Route::post('login', [UserController::class, 'login'])->middleware('auth.basic');
});

Route::group(['middleware' => ['auth:sanctum', 'policies.app']], function () {
    // Companies
    Route::prefix('company')->group(function () {
        // Rota 3
        Route::get('', [CompanyController::class, 'show']);
        // Rota 4
        Route::patch('', [CompanyController::class, 'update']);
    });

    // Users
    Route::prefix('users')->group(function () {
        // Rota 5
        Route::get('', [UserController::class, 'index']);

        // Rota 6
        Route::get('{id}', [UserController::class, 'show']);

        // Rota 7
        Route::post('', [UserController::class, 'create']);

        /**
         * Rota 8: A validaçao permite o usuário alterar a própria conta.
         * Assim qualquer usuário autenticado pode alterar seu próprio tipo para MANAGER
         */
        Route::patch('{id}', [UserController::class, 'update']);

        // Accounts
        Route::prefix('{id}/account')->group(function () {
            // Rota 10
            Route::get('', [AccountController::class, 'show']);

            // Rota 11
            Route::put('active', [AccountController::class, 'active']);

            // Rota 12
            Route::put('block', [AccountController::class, 'block']);

            // Rota 9
            Route::post('register', [AccountController::class, 'register']);
        });

        Route::prefix('{id}/card')->group(function () {
            // Rota 14
            Route::get('', [CardController::class, 'show']);

            // Rota 13
            Route::post('register', [CardController::class, 'register']);
        });
    });
});
