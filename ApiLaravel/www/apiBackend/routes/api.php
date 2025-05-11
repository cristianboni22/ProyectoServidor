<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;

// Rutas Visibles para todos
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/departamento', [DepartamentoController::class, 'index']);

// Rutas de autenticación
Route::middleware('auth:sanctum')->group(function () {

    //Rutas que puede ver Solo Empleado
    Route::get('/empleado/{dni}', [EmpleadoController::class, 'show']);

    //Rutas que solo puede el SuperAdmin
        // Rutas de Departamento
        Route::get('/departamento/{id}', [DepartamentoController::class, 'show']);
        Route::post('/departamento', [DepartamentoController::class, 'store']);
        Route::put('/departamento/{id}', [DepartamentoController::class, 'update']);
        Route::delete('/departamento/{id}', [DepartamentoController::class, 'destroy']);
        // Rutas de Empleado
        Route::post('/empleado', [EmpleadoController::class, 'store']);
        Route::get('/empleado', [EmpleadoController::class, 'index']);
        //Route::get('/empleado/{dni}', [EmpleadoController::class, 'show']);
        Route::put('/empleado/{dni}', [EmpleadoController::class, 'update']);
        Route::delete('/empleado/{dni}', [EmpleadoController::class, 'destroy']);
});
