<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required_without:nombre|email',
            'nombre' => 'required_without:correo',
            'contraseña' => 'required'
        ]);

        // Intentar login por correo

        if ($request->filled('correo') && Auth::attempt(['correo' => $request->correo, 'password' => $request->contraseña])) {
           return redirect()->route('inicio'); // Cambia '/' por la ruta que desees
        }

        // Intentar login por nombre
        if ($request->filled('nombre') && Auth::attempt(['nombre' => $request->nombre, 'password' => $request->contraseña])) {
           return redirect()->route('inicio');
        }

        return back()->withErrors(['correo' => 'Credenciales incorrectas'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}
