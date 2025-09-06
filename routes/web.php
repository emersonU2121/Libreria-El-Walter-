<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Página principal -> bienvenida
Route::get('/menu', function () {
    return view('inicio');
})->name('inicio');

// Registrar usuario -> formulario
Route::get('/usuarios/registrar', function () {
    return view('usuarios.registrar');
})->name('usuarios.registrar');

// Mostrar usuarios -> tabla
Route::get('/usuarios/mostrar', function () {
    $usuarios = User::all();
    return view('usuarios.mostrar', compact('usuarios'));
})->name('usuarios.mostrar');

// Formulario de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Procesar login
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

// Cerrar sesión
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');
