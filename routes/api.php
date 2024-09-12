<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PlatoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('isAuth' , [AuthController::class, 'isAuth']);

Route::group(['middleware' => ['auth:api']], function() {
    Route::get('logout', [AuthController::class, 'logout']);
});

Route::get('/platos', [PlatoController::class, 'index']);
Route::get('/platos/{id}', [PlatoController::class, 'show']);
Route::post('/platos', [PlatoController::class, 'store']);
Route::put('/platos/{id}', [PlatoController::class, 'update']); 
Route::delete('/platos/{id}', [PlatoController::class, 'destroy']);

// Ruta para obtener todos los platos de una categoria
Route::get('/platos/categoria/{id}', [PlatoController::class, 'platosPorCategoria']);

Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
Route::post('/categorias', [CategoriaController::class, 'store']);
Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
