<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Verifica si el usuario autenticado tiene rol de administrador.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Si no hay usuario autenticado o no es administrador
        if (!$user || ($user->rol ?? null) !== 'Administrador') {
            abort(403, 'Acceso denegado. Solo los administradores pueden entrar aquí.');
        }

        // Deja continuar la petición
        return $next($request);
    }
}