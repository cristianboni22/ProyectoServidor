<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/prueba', function (Request $request) {
    $respuesta = ['respuesta'=>"prueba"];
    return response()->json($respuesta);
});

// Rutas Login Usuarios
use App\Http\Controllers\AuthController;
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/user', function (Request $request) {
    if ($request->user()->tokenCan('server:read'))
        return response()->json(['respuesta' => $request->user()]);
    else
        return response()->json(['respuesta' => '¡No autorizado!'], 401);
})->middleware('auth:sanctum');
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Rutas Libros
use App\Http\Controllers\ApiController;
Route::get('/libros', [ApiController::class, 'index'])->middleware('auth:sanctum');
Route::get('/libro/{id}', [ApiController::class, 'show'])->middleware('auth:sanctum');
Route::post('/libros', [ApiController::class, 'store'])->middleware('auth:sanctum');
Route::put('/libro/{id}', [ApiController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/libro/{id}', [ApiController::class, 'destroy'])->middleware('auth:sanctum');

// Rutas Autores
use App\Http\Controllers\ApiControllerAuthor;
Route::get('/autores', [ApiControllerAuthor::class, 'index'])->middleware('auth:sanctum');
Route::get('/autor/{id}', [ApiControllerAuthor::class, 'show'])->middleware('auth:sanctum');
Route::post('/autores', [ApiControllerAuthor::class, 'store'])->middleware('auth:sanctum');
Route::put('/autor/{id}', [ApiControllerAuthor::class, 'update'])->middleware('auth:sanctum');
Route::delete('/autor/{id}', [ApiControllerAuthor::class, 'destroy'])->middleware('auth:sanctum');

// Rutas Prestamos
use App\Http\Controllers\ApiControllerLoan;
Route::get('/prestamos', [ApiControllerLoan::class, 'index'])->middleware('auth:sanctum');
Route::get('/prestamo/{id}', [ApiControllerLoan::class, 'show'])->middleware('auth:sanctum');
Route::post('/prestamos', [ApiControllerLoan::class, 'store'])->middleware('auth:sanctum');
Route::put('/prestamo/{id}', [ApiControllerLoan::class, 'update'])->middleware('auth:sanctum');
Route::delete('/prestamo/{id}', [ApiControllerLoan::class, 'destroy'])->middleware('auth:sanctum');

// Rutas Categorias
use App\Http\Controllers\ApiControllerCategory;
Route::get('/categorias', [ApiControllerCategory::class, 'index'])->middleware('auth:sanctum');
Route::get('/categoria/{id}', [ApiControllerCategory::class, 'show'])->middleware('auth:sanctum');
Route::post('/categorias', [ApiControllerCategory::class, 'store'])->middleware('auth:sanctum');
Route::put('/categoria/{id}', [ApiControllerCategory::class, 'update'])->middleware('auth:sanctum');
Route::delete('/categoria/{id}', [ApiControllerCategory::class, 'destroy'])->middleware('auth:sanctum');