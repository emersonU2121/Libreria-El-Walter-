<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Usuario;

class ComprasController extends Controller
{
    // Mostrar formulario de compra 
    public function create()
    {
        $productos = Producto::where('estado', 1)->get();
        return view('compras.registrar', compact('productos'));
    }

    // Procesar la compra 
    public function store(Request $request)
{
    $request->validate([
        'concepto_general' => 'required|string|max:255',
        'productos' => 'required|array|min:1',
        'productos.*.concepto' => 'required|string|max:255',
        'productos.*.id_producto' => 'required|exists:producto,idproducto',
        'productos.*.unidades' => 'required|integer|min:1',
        'productos.*.precio_compra' => 'required|numeric|min:0.01' // ← NUEVA VALIDACIÓN
    ]);

    DB::transaction(function () use ($request) {
        $usuario = Auth::user();
        
        if (!$usuario) {
            throw new \Exception('Usuario no autenticado');
        }

        // 1. Crear la compra
      $compra = Compra::create([
        'concepto' => $request->concepto_general, 
         'fecha' => now(),
         'idusuario' => $usuario->idusuario
]);

        // 2. Procesar productos
        foreach ($request->productos as $productoData) {
            $producto = Producto::find($productoData['id_producto']);

            if (!$producto) {
                throw new \Exception('Producto no encontrado: ' . $productoData['id_producto']);
            }

            // USAR EL PRECIO EDITABLE DEL FORMULARIO
            $precioUnitario = $productoData['precio_compra'];
            $precioTotal = $precioUnitario * $productoData['unidades'];

            // Crear detalle del producto
            DetalleCompra::create([
                'idcompra' => $compra->idcompra,
                'idproducto' => $productoData['id_producto'],
                'precio_total' => $precioTotal,
                'unidades' => $productoData['unidades'],
                'concepto' => $productoData['concepto']
            ]);

            // Actualizar stock del producto 
            $producto->stock += $productoData['unidades'];
            $producto->precio = $precioUnitario;

            //dd($producto->getDirty());//linea de prueba

            $producto->save();
        }
    });

    return redirect()->route('compras.mostrar')
        ->with('success', 'Compra registrada exitosamente');
}

   // Mostrar historial de compras (con filtro por fecha y paginación)
public function mostrar(\Illuminate\Http\Request $request)
{
    $q = \App\Models\Compra::with(['usuario', 'detalles.producto']);

    // Filtros por fecha (?desde=YYYY-MM-DD&hasta=YYYY-MM-DD)
    $desde = $request->date('desde');
    $hasta = $request->date('hasta');

    if ($desde && $hasta) {
        $q->whereBetween('fecha', [$desde, $hasta]);
    } elseif ($desde) {
        $q->whereDate('fecha', '>=', $desde);
    } elseif ($hasta) {
        $q->whereDate('fecha', '<=', $hasta);
    }

    $compras = $q->orderByDesc('fecha')
                 ->paginate(15)           // 👈 paginador
                 ->withQueryString();     // mantiene los filtros en los links

    return view('compras.mostrar', compact('compras'));
}


    // Ver detalles de una compra específica
    public function detalles($id)
    {
        $compra = Compra::with(['detalles.producto', 'usuario'])->findOrFail($id);
        return view('compras.detalles', compact('compra'));
    }

    
}