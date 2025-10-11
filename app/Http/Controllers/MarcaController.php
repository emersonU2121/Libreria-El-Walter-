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
     * Show the form for editing the specified resource.
     */
    public function edit(marca $marca)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, marca $marca)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(marca $marca)
    {
        //
    }
}
