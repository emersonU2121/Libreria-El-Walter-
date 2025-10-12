<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Producto;

class ProductoController extends Controller
{
    // ============================
    //  Muestra formulario registrar
    // ============================
    public function create()
    {
        // Suponiendo que ya tienes $marcas y $categorias cargadas en la BD
        $marcas = \DB::table('marca')->select('idmarca','nombre')->get();
        $categorias = \DB::table('categoria')->select('idcategoria','nombre')->get();

        return view('producto.registrarProducto', compact('marcas','categorias'));
    }

    // ============================
    //  Muestra listado
    // ============================
   public function mostrar()
{
    $productos = \DB::table('producto as p')
        ->leftJoin('marca as m', 'm.idmarca', '=', 'p.idmarca')
        ->leftJoin('categoria as c', 'c.idcategoria', '=', 'p.idcategoria')
        ->select(
            'p.*',
            'm.nombre as marca_nombre',
            'c.nombre as categoria_nombre'
        )
        ->orderBy('p.idproducto', 'asc')
        ->get();

    return view('producto.mostrarProducto', compact('productos'));
}


    // ============================
    //  Crear producto
    // ============================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idproducto'  => ['required','regex:/^(?!0+$)\d{1,20}$/','unique:producto,idproducto'],
            'nombre'      => 'required|string|max:255|unique:producto,nombre',
            'precio'      => ['required','regex:/^\d{1,9}([.,]\d{1,2})?$/'],
            'stock'       => 'required|integer|min:0',
            'idmarca'     => 'required|integer|exists:marca,idmarca',
            'idcategoria' => 'required|integer|exists:categoria,idcategoria',
        ],[
            'idproducto.regex'  => 'El ID debe tener 1–20 dígitos y no iniciar en 0.',
            'idproducto.unique' => 'El ID de producto ya existe.',
            'precio.regex'      => 'El precio debe tener solo números y hasta 2 decimales.',
        ]);

        $precio = number_format((float)str_replace(',', '.', $validated['precio']), 2, '.', '');

        $p = new Producto();
        $p->idproducto  = (string)$validated['idproducto'];
        $p->nombre      = $validated['nombre'];
        $p->precio      = $precio;
        $p->stock       = (int)$validated['stock'];
        $p->idmarca     = (int)$validated['idmarca'];
        $p->idcategoria = (int)$validated['idcategoria'];
        $p->estado      = $p->stock > 0 ? 'disponible' : 'agotado';
        $p->save();

        return redirect()->route('productos.mostrar')->with('ok','Producto creado exitosamente');
    }

    // ============================
    //  Editar producto
    // ============================
    public function update(Request $request, $id)
    {
        $p = Producto::find($id);
        if (!$p) {
            return redirect()->route('productos.mostrar')->with('error','Producto no encontrado');
        }

        $validated = $request->validate([
            'nombre'      => ['sometimes','string','max:255','unique:producto,nombre,'.$id.',idproducto'],
            'precio'      => ['sometimes','regex:/^\d{1,9}([.,]\d{1,2})?$/'],
            'stock'       => ['sometimes','integer','min:0'],
            'idmarca'     => ['sometimes','integer','exists:marca,idmarca'],
            'idcategoria' => ['sometimes','integer','exists:categoria,idcategoria'],
        ],[
            'nombre.unique' => 'El nombre de producto ya ha sido registrado.',
            'precio.regex'  => 'El precio debe tener solo números y hasta 2 decimales (coma o punto).',
        ]);

        if (array_key_exists('precio', $validated)) {
            $validated['precio'] = number_format(
                (float)str_replace(',', '.', $validated['precio']),
                2, '.', ''
            );
        }

        if (array_key_exists('stock', $validated)) {
            $validated['estado'] = ((int)$validated['stock'] > 0) ? 'disponible' : 'agotado';
        }

        $p->fill($validated)->save();

        return redirect()->route('productos.mostrar')->with('ok','Producto actualizado correctamente');
    }

    // ============================
    //  Marcar producto inactivo
    // ============================
    public function inactivo($id)
    {
        $p = Producto::find($id);
        if (!$p) return back()->with('error','Producto no encontrado');

        $p->estado = 'agotado';
        $p->save();

        return back()->with('ok','Producto dado de baja (agotado)');
    }

    // ============================
    //  Reactivar producto
    // ============================
    public function activo($id)
    {
        $p = Producto::find($id);
        if (!$p) return back()->with('error','Producto no encontrado');

        $p->estado = 'disponible';
        $p->save();

        return back()->with('ok','Producto reactivado correctamente');
    }
}