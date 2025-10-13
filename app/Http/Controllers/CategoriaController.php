<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $categorias = Categoria::buscar($q)
            ->orderByDesc('idcategoria')
            ->paginate(10)
            ->withQueryString();

        return view('categorias.mostrarC', compact('categorias','q'));
    }

    public function create()
    {
        return view('categorias.registrarC');
    }

    public function store(CategoriaRequest $request)
    {
        Categoria::create($request->validated());
        return redirect()->route('categorias.mostrarC')->with('ok','Categoría creada correctamente.');
    }

    public function update(CategoriaRequest $request, Categoria $categoria)
    {
        $categoria->update($request->validated());
        return back()->with('ok','Categoría actualizada.');
    }

    // Toggle de baja/reactivar (si existe columna 'estado')
    public function baja(Request $request, Categoria $categoria)
    {
        if (Schema::hasColumn('categoria','estado')) {
            $categoria->estado = ! (bool) $categoria->estado;
            $categoria->save();
            return back()->with('ok', $categoria->estado ? 'Categoría reactivada.' : 'Categoría dada de baja.');
        }

        // Si no usas 'estado', deja esta acción sin efecto o cambia a SoftDeletes según prefieras.
        return back()->with('ok','Acción realizada.');
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return back()->with('ok','Categoría eliminada.');
    }

    // app/Http/Controllers/CategoriaController.php

public function inactivo(Categoria $categoria) {
    if (Schema::hasColumn('categoria','estado')) {
        $categoria->update(['estado' => false]);
    }
    return back()->with('ok','Categoría dada de baja.');
}

public function activo(Categoria $categoria) {
    if (Schema::hasColumn('categoria','estado')) {
        $categoria->update(['estado' => true]);
    }
    return back()->with('ok','Categoría reactivada.');
}

public function validarNombre(Request $request) {
    $nombre = trim((string)$request->input('nombre'));
    $id     = $request->input('idcategoria');

    $q = Categoria::where('nombre', $nombre);
    if ($id) $q->where('idcategoria','!=',$id);

    return response()->json(['duplicado' => $q->exists()]);
}

}
