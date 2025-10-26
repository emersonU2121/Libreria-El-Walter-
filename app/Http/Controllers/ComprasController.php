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
            'productos' => 'required|array|min:1',
            'productos.*.concepto' => 'required|string|max:255',
            'productos.*.id_producto' => 'required|exists:producto,idproducto',
            'productos.*.unidades' => 'required|integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            // Obtener el ID del usuario autenticado CORRECTAMENTE
            $usuario = Auth::user();
            
            if (!$usuario) {
                throw new \Exception('Usuario no autenticado');
            }

            // 1. Crear la compra CON concepto (usando el primer concepto)
            $primerConcepto = $request->productos[0]['concepto'];
            
            $compra = Compra::create([
                'concepto' => $primerConcepto,
                'fecha' => now(),
                'idusuario' => $usuario->idusuario // ← USAR EL ID CORRECTO
            ]);

            // 2. Procesar productos (cada uno con su concepto en detalle_compra)
            foreach ($request->productos as $productoData) {
                $producto = Producto::find($productoData['id_producto']);

                if (!$producto) {
                    throw new \Exception('Producto no encontrado: ' . $productoData['id_producto']);
                }

                // Calcular el precio_total basado en producto.precio
                $precioTotal = $producto->precio * $productoData['unidades'];

                // Crear detalle del producto CON CONCEPTO
                DetalleCompra::create([
                    'idcompra' => $compra->idcompra,
                    'idproducto' => $productoData['id_producto'],
                    'precio_total' => $precioTotal,
                    'unidades' => $productoData['unidades'],
                    'concepto' => $productoData['concepto']
                ]);

                // 3. Actualizar stock del producto 
                $producto->stock += $productoData['unidades'];
                $producto->save();
            }
        });

        return redirect()->route('compras.mostrar')
            ->with('success', 'Compra registrada exitosamente');
    }

    // Mostrar historial de compras
    public function mostrar()
{
    $compras = Compra::with(['usuario', 'detalles.producto'])->latest()->get();
    return view('compras.mostrar', compact('compras'));
}

    // Ver detalles de una compra específica
    public function detalles($id)
    {
        $compra = Compra::with(['detalles.producto', 'usuario'])->findOrFail($id);
        return view('compras.detalles', compact('compra'));
    }
}