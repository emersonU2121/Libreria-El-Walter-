<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuario = Usuario::all();

        if ($usuario->isEmpty()) {
            $data = [
                'message' => 'No se encontraron usuarios',
            ];

            return response()->json($data, 200);
        }
        return response()->json($usuario, 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'nombre' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255|unique:usuario',
            'contraseña' => 'required|string|min:12',
            'rol' => 'required|string|max:50',
        ]);

        if($validator->fails()){
           $data = [
            'message' => 'Error de validacion',
            'errors' => $validator->errors(),
            'status' => 400
           ];
           return response()->json($data, 400);
        }
        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contraseña' => bcrypt($request->contraseña),
            'rol' => $request->rol,
        ]);

        if(!$usuario){
            $data = [
                'message' => 'Error al crear el usuario',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Usuario creado exitosamente',
            'usuario' => $usuario,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

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

    public function update(Request $request, $id) {
        $usuario = Usuario::find($id);

        if(!$usuario){
            $data =[
                'message' => 'Usuario no encontrado',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $validator = Validator::make($request->all(),[
            'nombre' => 'sometimes|required|string|max:255',
            'correo' => 'sometimes|required|string|email|max:255|unique:usuario,correo,'.$id.',idUsuario',
            'contraseña' => 'sometimes|required|string|min:12',
            'rol' => 'sometimes|required|string|max:50',
        ]);

        if($validator->fails()){
            $data = [
                'message' => 'Error de validacion',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

       $usuario->nombre = $request->has('nombre') ? $request->nombre : $usuario->nombre;
       $usuario->correo = $request->has('correo') ? $request->correo : $usuario->correo;
       $usuario->rol = $request->has('rol') ? $request->rol : $usuario->rol;
       $usuario->contraseña = $request->has('contraseña') ? bcrypt($request->contraseña) : $usuario->contraseña;

       $usuario->save();

       $data = [
        'message' => 'Usuario actualizado exitosamente',
        'usuario' => $usuario,
        'status' => 200
       ];

       return response()->json($data, 200);
   }

   public function updatePartial(Request $request, $id){

    $usuario = Usuario::find($id);

    if(!$usuario){
        $data =[
            'message' => 'Usuario no encontrado',
            'status' => 404
        ];
        return response()->json($data, 404);
    }

    $validator = Validator::make($request->all(),[
        'nombre' => 'sometimes|required|string|max:255',
        'correo' => 'sometimes|required|string|email|max:255|unique:usuario,correo,'.$id.',idUsuario',
        'contraseña' => 'sometimes|required|string|min:12',
        'rol' => 'sometimes|required|string|max:50',
    ]);

    if($validator->fails()){
        $data = [
            'message' => 'Error de validacion',
            'errors' => $validator->errors(),
            'status' => 400
        ];
        return response()->json($data, 400);
    }

    if($request->has('nombre')){
        $usuario->nombre = $request->nombre;
    }

    if($request->has('correo')){
        $usuario->correo = $request->correo;
    }

    if($request->has('rol')){
        $usuario->rol = $request->rol;
    }

    if($request->has('contraseña')){
        $usuario->contraseña = bcrypt($request->contraseña);
    }
    $usuario->save();


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