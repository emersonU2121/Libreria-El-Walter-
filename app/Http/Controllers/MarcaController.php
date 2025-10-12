<?php

namespace App\Http\Controllers;

use App\Models\marca;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $marcas = Marca::all();

        if($marcas->empty())
        {
           return response()->json(['message'=> 'No se encontraron Marcas']);
        }

        return response()->json($marcas, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas.Registrar');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
             'nombre' => 'required|string|max:255|unique:usuario,nombre'
        ],[
            
            'nombre.unique' => 'La marca ya ha sido registrado.'
        ]);

        if($validator->fail())
        {
            if(!$request->expectsJson()){
                return back()->withErrors($validator)->withInput();
            }
            return response()->json([
                'message' => 'Error de validacion',
                'errors'  => $validator->errors(),
                'status'  => 400,
            ], 400);
        }
        $marca = new Marca();
        $marca->nombre = $request->input('nombre');
        $marca->save();

        if(!$request->expectsJson()){
            return redirect()
            ->route('marcas.registrar')
            ->with('ok', 'Marca registrada exitosamente');
        }
        return response()->json([
            'message' => 'Marca registrada exitosamente',
            'status'  => 201,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $marca = Marca::find($id);
        if(!$marca)
        {
            return response()->json(['message'=> 'Marca no encontrada', 'status'=> 404], 404);
        }
        return response()->json(['message'=>$marca, 'status'=> 200], 200);

    }

   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = Marca::find($id);

        if(!$marca){
           if($request->expectsJson()){
                return back()->with('error', 'No se han encontrado marcas');
           }
            return response()->json([
            'succes'=>false,
            'message'=>'No se han encontrado marcas'
        ], 404);

        }
        

        $validator = Validator::make($request->all(), [
            'nombre'=> ['required','string','max:255','unique::marca,nombre'.$id.'idMarca']
        ],[
            'nombre.unique'=> 'La marca ya ha sido registrada.'
        ]);

        if($validator->fails()){
            if(!$request->expectsJson()){
                return back()->withErrors($validator)->withInput()->with('modal','editar');
            }
         }

         if($request->has('nombre')){
                $marca->nombre = $request->input('nombre');
            }
        $marca->save();
    }

    //validar marca
    public function validarMarca(Request $request)
    {
        $nombre = $request->input('nombre');
        $idMarca = $request->input('idMarca');
        $existe = \App\Models\Marca::where('nombre', $nombre)
        ->when($idMarca, function($query) use ($idMarca) {
            $query->where('idMarca', '!=', $idMarca);
        })
        ->exists();

        return response()->json(['duplicado'=>$existe]);
    }

    //marca activa o inactiva 
    public function inactivo($id){
        $marca = Marca::find($id);
        
        if(!$marca)
        {
            return request()->expectsJson();
            
        }

    }
   
}
