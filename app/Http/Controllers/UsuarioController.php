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
}
