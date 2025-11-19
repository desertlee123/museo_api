<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticuloController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\GaleriaController;
use App\Http\Controllers\Api\ComentarioController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\VideoController;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

// Rutas públicas (sin token)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Artículos
Route::get('/articulos/recientes', [ArticuloController::class, 'recientes']);
Route::get('/articulos/galeria/{id_galeria}', [ArticuloController::class, 'porGaleria']);
Route::get('/articulos/buscar', [ArticuloController::class, 'buscar']);

// Guardados (usuario autenticado)
Route::middleware('auth:sanctum')->get('/usuarios/articulos/guardados', [UsuarioController::class, 'savedArticles']);
Route::middleware('auth:sanctum')->post('/usuarios/articulos/guardar', [UsuarioController::class, 'toggleGuardarArticulo']);

Route::get('/articulos', [ArticuloController::class, 'index']);
Route::get('/articulos/{id}', [ArticuloController::class, 'show']);

// Rutas protegidas (requieren token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/validate-token', [AuthController::class, 'validateToken']);

    // Comentarios admin (protegidas)
    Route::get('/comentarios/buscar', [ComentarioController::class, 'buscar']);
    Route::get('/comentarios/{articuloId}/{usuarioId}', [ComentarioController::class, 'indexByArticuloAndUsuario']);
    Route::post('/comentarios', [ComentarioController::class, 'adminModify']); // update/delete

    // Crear comentario (usuario autenticado)
    Route::middleware('auth:sanctum')->post('/usuarios/comentar', [ComentarioController::class, 'storeUserComment']);
});

// Ruta para obtener usuario por ID
Route::get('/usuarios/{id}', [UsuarioController::class, 'show']);

// Categorías
Route::get('/categorias', [CategoriaController::class, 'index']);

// Galerías
Route::get('/galerias/buscar', [GaleriaController::class, 'buscar']);
Route::get('/galerias', [GaleriaController::class, 'index']);
Route::get('/galerias/{id}', [GaleriaController::class, 'show']);

// Comentarios públicos
Route::get('/comentarios/{articuloId}', [ComentarioController::class, 'indexByArticulo']);

Route::middleware('auth:sanctum')->patch('/user/role', [AuthController::class, 'updateRole']);

Route::get('/videos', [VideoController::class, 'index']);
