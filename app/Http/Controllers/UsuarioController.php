<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    /**
     * Mostrar formulario de registro (vista Blade).
     */
    public function create()
    {
        return view('usuarios.registrar');
    }

    /**
     * (Opcional) Listado JSON de usuarios (modo API).
     */
    public function index()
    {
        $usuarios = Usuario::all();

        if ($usuarios->isEmpty()) {
            return response()->json(['message' => 'No se encontraron usuarios'], 200);
        }

        return response()->json($usuarios, 200);
    }

    /**
     * Guardar un usuario (sirve para Web y API).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'     => 'required|string|max:255',
            'correo'     => 'required|string|email|max:255|unique:usuario,correo',
            'contraseña' => 'required|string|min:12',
            'rol'        => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            if (!$request->expectsJson()) {
                return back()->withErrors($validator)->withInput();
            }
            return response()->json([
                'message' => 'Error de validacion',
                'errors'  => $validator->errors(),
                'status'  => 400,
            ], 400);
        }

        $usuario = new Usuario();
        $usuario->nombre = $request->input('nombre');
        $usuario->correo = $request->input('correo');
        // Evitar acceder con propiedad que contiene 'ñ'
        $usuario->setAttribute('contraseña', bcrypt($request->input('contraseña')));
        $usuario->rol    = $request->input('rol');
        $usuario->activo = true;
        $usuario->save();

        if (!$request->expectsJson()) {
            return redirect()
                ->route('usuarios.registrar')
                ->with('ok', 'Usuario creado exitosamente');
        }

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'usuario' => $usuario,
            'status'  => 201,
        ], 201);
    }

    /**
     * (Opcional) Mostrar 1 usuario en JSON (modo API).
     */
    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado', 'status' => 404], 404);
        }

        return response()->json(['message' => $usuario, 'status' => 200], 200);
    }

    /**
     * Editar usuario (desde modal). Web (redirect) y API (JSON).
     */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            if (!$request->expectsJson()) {
                return back()->with('error', 'Usuario no encontrado');
            }
            return response()->json(['message' => 'Usuario no encontrado', 'status' => 404], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre'     => ['required','string','max:255'],
            'correo'     => ['required','string','email','max:255','unique:usuario,correo,'.$id.',idusuario'],
            'rol'        => ['nullable','string','max:50'],
            'contraseña' => ['nullable','string','min:12'],
        ]);

        if ($validator->fails()) {
            if (!$request->expectsJson()) {
                return back()->withErrors($validator)->withInput();
            }
            return response()->json([
                'message' => 'Error de validacion',
                'errors'  => $validator->errors(),
                'status'  => 400,
            ], 400);
        }

        $usuario->nombre = $request->input('nombre');
        $usuario->correo = $request->input('correo');
        if ($request->filled('rol')) {
            $usuario->rol = $request->input('rol');
        }
        if ($request->filled('contraseña')) {
            $usuario->setAttribute('contraseña', bcrypt($request->input('contraseña')));
        }
        $usuario->save();

        if (!$request->expectsJson()) {
            return back()->with('ok', 'Usuario actualizado correctamente');
        }

        return response()->json([
            'message' => 'Usuario actualizado',
            'usuario' => $usuario,
            'status'  => 200,
        ], 200);
    }

    /**
     * (Opcional) Actualización parcial (API).
     */
    public function updatePartial(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado', 'status' => 404], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre'     => ['sometimes','required','string','max:255'],
            'correo'     => ['sometimes','required','string','email','max:255','unique:usuario,correo,'.$id.',idusuario'],
            'rol'        => ['sometimes','nullable','string','max:50'],
            'contraseña' => ['sometimes','nullable','string','min:12'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors'  => $validator->errors(),
                'status'  => 400,
            ], 400);
        }

        if ($request->has('nombre'))     $usuario->nombre = $request->input('nombre');
        if ($request->has('correo'))     $usuario->correo = $request->input('correo');
        if ($request->has('rol'))        $usuario->rol    = $request->input('rol');
        if ($request->has('contraseña')) $usuario->setAttribute('contraseña', bcrypt($request->input('contraseña')));

        $usuario->save();

        return response()->json([
            'message' => 'Usuario actualizado parcialmente',
            'usuario' => $usuario,
            'status'  => 200,
        ], 200);
    }

    /**
     * Marcar usuario como inactivo (Dar de baja).
     * Soporta Web (redirect) y API (JSON).
     */
    public function inactivo($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return request()->expectsJson()
                ? response()->json(['message' => 'Usuario no encontrado', 'status' => 404], 404)
                : back()->with('error', 'Usuario no encontrado');
        }

        $usuario->activo = false;
        $usuario->save();

        return request()->expectsJson()
            ? response()->json(['message' => 'Usuario dado de baja', 'status' => 200], 200)
            : back()->with('ok', 'Usuario dado de baja');
    }

    /**
     * Marcar usuario como activo (Reactivar).
     * Soporta Web (redirect) y API (JSON).
     */
    public function activo($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return request()->expectsJson()
                ? response()->json(['message' => 'Usuario no encontrado', 'status' => 404], 404)
                : back()->with('error', 'Usuario no encontrado');
        }

        $usuario->activo = true;
        $usuario->save();

        return request()->expectsJson()
            ? response()->json(['message' => 'Usuario reactivado', 'status' => 200], 200)
            : back()->with('ok', 'Usuario reactivado');
    }
}
