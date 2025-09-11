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
        return Str::lower($userKey).'|'.$request->ip();
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo'      => 'required|email',
            'contraseña'  => 'required'
        ]);

        $key         = $this->throttleKey($request);
        $maxAttempts = 3;     // intentos permitidos
        $decaySecs   = 30;    // bloqueo en segundos

        // Si ya está bloqueado, mostramos mensaje + tiempo restante
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withInput($request->only('correo'))
                ->with('lock_seconds', $seconds)
                ->withErrors(['login' => "Demasiados intentos. Inténtalo de nuevo en {$seconds} segundos."]);
        }

        // Intento de login
        $ok = Auth::attempt(['correo' => $request->correo, 'password' => $request->contraseña]);

        if ($ok) {
            RateLimiter::clear($key);          // limpia contador
            $request->session()->regenerate(); // seguridad de sesión
            return redirect()->route('inicio');
        }

        // Falló: cuenta el intento y responde con intentos restantes
        RateLimiter::hit($key, $decaySecs);

        $restantes = max(0, $maxAttempts - RateLimiter::attempts($key));
        return back()
            ->withInput($request->only('correo'))
            ->withErrors(['login' => "Credenciales incorrectas. Intentos restantes: {$restantes}"]);
    }

    public function logout(Request $request)
    {
        // Si usas autenticación por sesión (no tokens API):
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login'); // o la ruta que prefieras
    }
}
