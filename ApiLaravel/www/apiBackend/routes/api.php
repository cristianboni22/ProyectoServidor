<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpleadoController;

Route::get('/hola', [DepartamentoController::class, 'index']);

// Rutas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
// Rutas de Departamento (accesibles sin autenticación)
Route::get('/departamento', [DepartamentoController::class, 'index']); // Obtener todos los departamentos
Route::get('/departamento/{id}', [DepartamentoController::class, 'show']); // Obtener departamento por ID

// Rutas de Empleado (accesibles sin autenticación)
Route::get('/empleado', [EmpleadoController::class, 'index']); // Obtener todos los empleados
Route::get('/empleado/{dni}', [EmpleadoController::class, 'show']); // Obtener empleado por DNI

// Rutas de Departamento (solo accesibles por administrador o usuarios autenticados)
Route::post('/departamento', [DepartamentoController::class, 'store']);  // Crear nuevo departamento
Route::put('/departamento/{id}', [DepartamentoController::class, 'update']); // Modificar departamento
Route::delete('/departamento/{id}', [DepartamentoController::class, 'destroy']); // Eliminar departamento

// Rutas de Empleado (solo accesibles por administrador o usuarios autenticados)
Route::post('/empleado', [EmpleadoController::class, 'store']);  // Crear nuevo empleado
Route::put('/empleado/{dni}', [EmpleadoController::class, 'update']); // Modificar empleado
Route::delete('/empleado/{dni}', [EmpleadoController::class, 'destroy']); // Eliminar empleado
});