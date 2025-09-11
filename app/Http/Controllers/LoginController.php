<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Genera una clave por usuario/ip para el conteo de intentos
    protected function throttleKey(Request $request): string
    {
        $userKey = $request->input('correo') ?: 'guest';
        return Str::lower($userKey) . '|' . $request->ip();
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required'
        ]);

        $key = $this->throttleKey($request);
        $maxAttempts = 3;     // intentos permitidos
        $decaySecs = 30;      // bloqueo en segundos

        // Si ya está bloqueado, mostramos mensaje + tiempo restante
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withInput($request->only('correo'))
                ->with('lock_seconds', $seconds)
                ->withErrors(['login' => "Demasiados intentos. Inténtalo de nuevo en {$seconds} segundos."]);
        }

        // Buscar usuario por correo
        $user = Usuario::where('correo', $request->correo)->first();

        // Validación de existencia y contraseña
        if (!$user || !Hash::check($request->contraseña, $user->contraseña)) {
            RateLimiter::hit($key, $decaySecs);
            $restantes = max(0, $maxAttempts - RateLimiter::attempts($key));
            return back()
                ->withInput($request->only('correo'))
                ->withErrors(['login' => "Credenciales incorrectas. Intentos restantes: {$restantes}"]);
        }

        // Validar si está activo
        if (!$user->activo) {
            return back()
                ->withInput($request->only('correo'))
                ->withErrors(['login' => 'Esta cuenta está inactiva.']);
        }

        // Inicia sesión
        Auth::login($user);
        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect()->route('inicio');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
