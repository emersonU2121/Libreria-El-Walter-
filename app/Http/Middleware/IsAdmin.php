<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Maneja una solicitud entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo permite acceso si está logueado y su rol es Administrador
        if (auth()->check() && auth()->user()->rol === 'Administrador') {
            return $next($request);
        }

        // Si no es administrador, mostrar error 403
        abort(403, 'Acceso no autorizado');
    }
}
