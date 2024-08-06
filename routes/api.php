<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PlatoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


// Open routes -> Token not required
Route::post('register' , [ApiController::class , 'register']);
Route::post('login' , [ApiController::class , 'login']);

// Protected routes -> Token required

Route::group([
    "middleware" => ["auth:api"]
], function(){
    Route::get("profile", [ApiController::class, "profile"]);
    Route::get("refresh", [ApiController::class, "refreshToken"]);
    Route::get("logout", [ApiController::class, "logout"]);
});


// Rutas de los platos -> index, show opened 
Route::get('/platos' , [PlatoController::class , 'index']);
Route::get('/platos/{id}' , [PlatoController::class , 'show']);
Route::post('/platos/store' , [PlatoController::class , 'store']);
Route::put('/platos/update/{id}' , [PlatoController::class , 'update']); 
Route::delete('/platos/delete/{id}' , [PlatoController::class , 'destroy']);
// Ruta para obtener todos los platos de una categoria
Route::get('/platos/categoria/{id}' , [PlatoController::class , 'platosPorCategoria']);

// Rutas de las categorias -> index, show opened
Route::get('/categorias' , [CategoriaController::class , 'index']);
Route::get('/categorias/{id}' , [CategoriaController::class , 'show']);
Route::post('/categorias/store' , [CategoriaController::class , 'store']);
Route::put('/categorias/update/{id}' , [CategoriaController::class , 'update']);
Route::delete('/categorias/delete/{id}' , [CategoriaController::class , 'destroy']); 

