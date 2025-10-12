<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductoController;


// Página principal
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return view('auth.login');
})->name('login');


Route::get('/inicio', function () {
    return view('inicio');
})->name('inicio');

// ====== USUARIOS ======

// Formulario de registro (GET)
Route::get('/usuarios/registrar', [UsuarioController::class, 'create'])
     ->name('usuarios.registrar');

// Guardar usuario (POST)
Route::post('/usuarios', [UsuarioController::class, 'store'])
     ->name('usuarios.store');

// Editar usuario (PUT)  <-- usado por el modal Editar
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');


// Dar de baja (PUT)     <-- usado por el modal Confirmación (cuando está activo)
Route::put('/usuarios/{id}/inactivar', [UsuarioController::class, 'inactivo'])
     ->name('usuarios.inactivo');

// Reactivar (PUT)       <-- usado por el modal Confirmación (cuando está inactivo)
Route::put('/usuarios/{id}/activar', [UsuarioController::class, 'activo'])
     ->name('usuarios.activo');

// Mostrar usuarios (tabla)
Route::get('/usuarios/mostrar', function () {
    $usuarios = Usuario::all();
    return view('usuarios.mostrar', compact('usuarios'));
})->name('usuarios.mostrar');

// ====== AUTH ======

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

//validar nombre 
Route::post('/usuarios/validar-nombre', [UsuarioController::class, 'validarNombre'])->name('usuarios.validar-nombre');

// Resetear contraseña - formulario para solicitar el enlace de restablecimiento

Route::get('/password/reset', [PasswordController::class, 'showRequestForm']);
Route::post('/password/email', [PasswordController::class, 'sendResetLink']);
Route::get('/password/reset/{token}', [PasswordController::class, 'showResetForm']);
Route::post('/password/reset', [PasswordController::class, 'resetPassword']);

Route::post('/login', [App\Http\Controllers\LoginController::class, 'login'])
    ->name('login.post'); // ← sin ->middleware('throttle:3,1')

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');


Route::get('/productos/registrar', [ProductoController::class, 'create'])->name('productos.registrar');
Route::get('/productos/mostrar',   [ProductoController::class, 'mostrar'])->name('productos.mostrar');

Route::post('/productos',                [ProductoController::class, 'store'])->name('productos.store');
Route::put('/productos/{id}',            [ProductoController::class, 'update'])->name('productos.update');
Route::put('/productos/{id}/inactivo',   [ProductoController::class, 'inactivo'])->name('productos.inactivo');
Route::put('/productos/{id}/activo',     [ProductoController::class, 'activo'])->name('productos.activo');