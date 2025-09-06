<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Http\Controllers\UsuarioController;

// Página principal
Route::get('/menu', function () {
    return view('inicio');
})->name('inicio');

// FORMULARIO (GET) — usa UsuarioController@create
Route::get('/usuarios/registrar', [UsuarioController::class, 'create'])
     ->name('usuarios.registrar');

// GUARDAR (POST) — usa UsuarioController@store
Route::post('/usuarios', [UsuarioController::class, 'store'])
     ->name('usuarios.store');

// Mostrar usuarios (tabla sencilla)
Route::get('/usuarios/mostrar', function () {
    $usuarios = Usuario::all();
    return view('usuarios.mostrar', compact('usuarios'));
})->name('usuarios.mostrar');

// Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('inicio')->with('success', 'Bienvenido de nuevo!');
    }

    return back()->withErrors([
        'email' => 'Las credenciales no son correctas.',
    ]);
})->name('login.post');

Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
