<?php

namespace App\Http\Controllers;

use App\Models\marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Soporta ?buscar=... y ?perPage=...
    $buscar  = $request->get('buscar');
    $perPage = (int) $request->get('perPage', 10);
    if ($perPage <= 0) $perPage = 15;

    $marcas = marca::when($buscar, fn ($q, $b) =>
                    $q->where('nombre', 'like', "%{$b}%"))
                ->orderBy('nombre')
                ->paginate($perPage)          
                ->withQueryString();          

    if ($marcas->total() === 0) {
        return response()->json(['message' => 'No se encontraron Marcas'], 200);
    }

    return response()->json($marcas, 200);

    // Si prefieres solo Prev/Siguiente:
    // ->simplePaginate($perPage)->withQueryString();
}

public function mostrar(Request $request)
{
    $buscar  = $request->get('buscar');
    $perPage = (int) $request->get('perPage', 10);
    if ($perPage <= 0) $perPage = 10;

    $marcas = marca::when($buscar, fn ($q, $b) =>
                    $q->where('nombre', 'like', "%{$b}%"))
                ->orderBy('nombre')
                ->paginate($perPage)          
                ->withQueryString();          

    return view('marcas.mostrar_marca', compact('marcas', 'buscar'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('marcas.registrar_marca');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
             'nombre' => 'required|string|max:255|unique:marca,nombre'
        ],[
            
            'nombre.unique' => 'La marca ya ha sido registrado.'
        ]);

        if($validator->fails())
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
        $marca = new marca();
        $marca->nombre = $request->input('nombre');
        $marca->save();

        if(!$request->expectsJson()){
            return redirect()
            ->route('marcas.mostrar')
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
        $marca = marca::find($id);
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
            return response()->json([
                'success'=>false,
                'message'=>'No se han encontrado marcas'
            ], 404);
        }
        return back()->with('error', 'No se han encontrado marcas');
    }

    // Validación corregida
    $validator = Validator::make($request->all(), [
        'nombre' => ['required', 'string', 'max:255', 'unique:marca,nombre,'.$id.',idmarca']
    ],[
        'nombre.unique' => 'La marca ya ha sido registrada.'
    ]);

    if($validator->fails()){
        if(!$request->expectsJson()){
            return back()->withErrors($validator)->withInput()->with('modal','editar');
        }
        return response()->json([
            'message' => 'Error de validacion',
            'errors'  => $validator->errors(),
            'status'  => 400,
        ], 400);
    }

    if($request->has('nombre')){
        $marca->nombre = $request->input('nombre');
    }
    
    $marca->save();

    if(!$request->expectsJson()){
    
        return redirect()->route('marcas.mostrar')->with('ok', 'Marca actualizada exitosamente');
    }
    
    return response()->json([
        'message' => 'Marca actualizada exitosamente',
        'status'  => 200,
    ], 200);
}


    public function destroy(Request $request, $id)
{
    $marca = marca::find($id);

    if (!$marca) {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Marca no encontrada'], 404);
        }
        return back()->with('error', 'Marca no encontrada');
    }

    try {
        // Verificar si hay productos asociados a esta marca
        if (\App\Models\Producto::where('idMarca', $id)->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No se puede eliminar la marca porque tiene productos asociados',
                    'status' => 400
                ], 400);
            }
            return back()->with('error', 'No se puede eliminar la marca porque tiene productos asociados');
        }

        $marca->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Marca eliminada correctamente', 
                'status' => 200
            ], 200);
        }

        return redirect()->route('marcas.mostrar')->with('ok', 'Marca eliminada correctamente');
        
    } catch (\Illuminate\Database\QueryException $e) {
        // Capturar error de clave foránea
        $errorCode = $e->errorInfo[1];
        
        if ($errorCode == 1451) { // Código de error para restricción de clave foránea
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No se puede eliminar la marca porque está siendo utilizada en otros registros',
                    'status' => 400
                ], 400);
            }
            return back()->with('error', 'No se puede eliminar la marca porque está siendo utilizada en productos');
        }

        // Otro tipo de error
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Error al eliminar la marca', 
                'error' => $e->getMessage()
            ], 500);
        }
        return back()->with('error', 'Error al eliminar la marca: ' . $e->getMessage());
        
    } catch (\Throwable $e) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Error al eliminar la marca', 
                'error' => $e->getMessage()
            ], 500);
        }
        return back()->with('error', 'Error al eliminar la marca');
    }
}
   
}
