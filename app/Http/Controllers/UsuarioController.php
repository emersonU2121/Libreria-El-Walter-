<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    // Muestra el formulario (vista Blade)
    public function create()
    {
        return view('usuarios.registrar');
    }

    // API: lista JSON (opcional)
    public function index()
    {
        $usuario = Usuario::all();

        if ($usuario->isEmpty()) {
            return response()->json(['message' => 'No se encontraron usuarios'], 200);
        }
        return response()->json($usuario, 200);
    }

    // Guarda (sirve para Web y API)
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nombre'     => 'required|string|max:255',
            'correo'     => 'required|string|email|max:255|unique:usuario,correo',
            'contraseña' => 'required|string|min:12',
            'rol'        => 'required|string|max:50',
        ]);

        if($validator->fails()){
           if (!$request->expectsJson()) {
               return back()->withErrors($validator)->withInput();
           }
           return response()->json([
               'message' => 'Error de validacion',
               'errors'  => $validator->errors(),
               'status'  => 400
           ], 400);
        }

        $usuario = Usuario::create([
            'nombre'     => $request->input('nombre'),
            'correo'     => $request->input('correo'),
            'contraseña' => bcrypt($request->input('contraseña')),
            'rol'        => $request->input('rol'),
        ]);

        if(!$usuario){
            if (!$request->expectsJson()) {
                return back()->with('error', 'Error al crear el usuario')->withInput();
            }
            return response()->json(['message' => 'Error al crear el usuario','status'=>500], 500);
        }

        if (!$request->expectsJson()) {
            return redirect()->route('usuarios.registrar')->with('ok', 'Usuario creado exitosamente');
        }

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'usuario' => $usuario,
            'status'  => 201
        ], 201);
    }

    // API: mostrar uno (opcional)
    public function show($id){
        $usuario = Usuario::find($id);
        if(!$usuario){
            $data = [
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        
        $data = [
            'message' => $usuario,
            'status' => 200

        ];
        return response()->json($data, 200);


    }

    public function inactivo($id){
        $usuario = Usuario::find($id);
        if(!$usuario){
            $data=[
                'message' => 'No se ha podido inactivar, usuario no encontrado',
                'status'=>404
            ];
            return response()->json($data,404);
        }
        $usuario->activo = false;
        $usuario->save();

        $data = [
            'message' => 'Usuario marcado como inactivo exitosamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    // API: update completo (opcional)
    public function update(Request $request, $id) {
        $usuario = Usuario::find($id);
        if(!$usuario){
            return response()->json(['message' => 'Usuario no encontrado','status' => 404], 404);
        }

        $validator = Validator::make($request->all(),[
            'nombre'     => 'sometimes|required|string|max:255',
            'correo'     => 'sometimes|required|string|email|max:255|unique:usuario,correo,'.$id.',idusuario', // <-- idusuario
            'contraseña' => 'sometimes|required|string|min:12',
            'rol'        => 'sometimes|required|string|max:50',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Error de validacion',
                'errors'  => $validator->errors(),
                'status'  => 400
            ], 400);
        }

        $usuario->nombre     = $request->has('nombre')     ? $request->nombre     : $usuario->nombre;
        $usuario->correo     = $request->has('correo')     ? $request->correo     : $usuario->correo;
        $usuario->rol        = $request->has('rol')        ? $request->rol        : $usuario->rol;
        $usuario->contraseña = $request->has('contraseña') ? bcrypt($request->contraseña) : $usuario->contraseña;

        $usuario->save();

        return response()->json([
            'message' => 'Usuario actualizado exitosamente',
            'usuario' => $usuario,
            'status'  => 200
        ], 200);
    }

    // API: update parcial (opcional)
    public function updatePartial(Request $request, $id){
        $usuario = Usuario::find($id);
        if(!$usuario){
            return response()->json(['message' => 'Usuario no encontrado','status' => 404], 404);
        }

        $validator = Validator::make($request->all(),[
            'nombre'     => 'sometimes|required|string|max:255',
            'correo'     => 'sometimes|required|string|email|max:255|unique:usuario,correo,'.$id.',idusuario', // <-- idusuario
            'contraseña' => 'sometimes|required|string|min:12',
            'rol'        => 'sometimes|required|string|max:50',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Error de validacion',
                'errors'  => $validator->errors(),
                'status'  => 400
            ], 400);
        }

        if($request->has('nombre'))     $usuario->nombre     = $request->nombre;
        if($request->has('correo'))     $usuario->correo     = $request->correo;
        if($request->has('rol'))        $usuario->rol        = $request->rol;
        if($request->has('contraseña')) $usuario->contraseña = bcrypt($request->contraseña);

        $usuario->save();

        return response()->json([
            'message' => 'Usuario actualizado parcialmente',
            'usuario' => $usuario,
            'status'  => 200
        ], 200);
   }

   public function activo($id){
    $usuario = Usuario::find($id);

    if(!$usuario){
        $data =[
            'message' => 'Usuario no encontrado',
            'status' => 404
        ];
        return response()->json($data, 404);
    }

    $usuario->activo = true;
    $usuario->save();

    $data = [
        'message' => 'Usuario restaurado exitosamente',
        'status' => 200
    ];

    return response()->json($data, 200);
}

}