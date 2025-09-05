<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuarioController;

// Ruta para el CRUD de usuarios
Route::apiResource('usuarios', UsuarioController::class);