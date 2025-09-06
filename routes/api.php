<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;

Route::get('/usuario', [UsuarioController::class, 'index']);

Route::get('/usuario/{id}', [UsuarioController::class, 'show']);

Route::post('/usuario', [UsuarioController::class, 'store']);


Route::put('/usuario/{id}', [UsuarioController::class, 'update']);

Route::patch('/usuario/{id}', [UsuarioController::class, 'updatePartial']);

Route::put('/usuario/activo/{id}', [UsuarioController::class, 'activo']);

Route::delete('/usuario/{id}', [UsuarioController::class, 'inactivo']);
