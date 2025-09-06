<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsuarioController;

Route::get('/usuario', [UsuarioController::class, 'index']);

Route::get('/usuario/{id}', function () {
    return 'Obteniendo un usuario';
});

Route::post('/usuario', [UsuarioController::class, 'store']);


Route::put('/usuario/{id}', [UsuarioController::class, 'update']);


Route::delete('/usuario/{id}', function () {
    return 'Eliminando Usuario';
});
