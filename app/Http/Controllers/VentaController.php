<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class VentaController extends Controller
{
    /**
     * Muestra la vista del Punto de Venta (POS).
     */
    public function create()
    {
        // Cargamos solo productos activos y con stock
        $productos = Producto::where('estado', 1)
                            ->where('stock', '>', 0)
                            ->orderBy('nombre', 'asc')
                            ->get();
                            
        return view('ventas.registrar', compact('productos'));
    }

    /**
     * Procesa y registra la nueva venta.
     * Esta es la lógica crítica que descuenta el stock.
     */
    public function store(Request $request)
    {
        $request->validate([
            'total' => 'required|numeric|min:0.01',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:producto,idproducto',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_venta' => 'required|numeric|min:0',
        ]);

        $carrito = $request->productos;
        $totalVenta = $request->total;
        $usuario = Auth::user();

        try {
            DB::beginTransaction();

            // 1. Validar el stock ANTES de hacer cualquier cosa
            foreach ($carrito as $item) {
                $producto = Producto::find($item['id']);
                if ($producto->stock < $item['cantidad']) {
                    // Si no hay stock, cancela toda la transacción
                    throw new \Exception('Stock insuficiente para el producto: ' . $producto->nombre);
                }
            }

            // 2. Crear la Venta
            $venta = Venta::create([
                'idusuario' => $usuario->idusuario,
                'fecha' => now(),
                'total' => $totalVenta
            ]);

            // 3. Crear los Detalles y DESCONTAR el stock
            foreach ($carrito as $item) {
                // Crear el detalle
                DetalleVenta::create([
                    'idventa' => $venta->idventa,
                    'idproducto' => $item['id'],
                    'unidades' => $item['cantidad'],
                    'precio_total' => $item['cantidad'] * $item['precio_venta']
                ]);

                // Descontar el stock
                $producto = Producto::find($item['id']);
                $producto->stock -= $item['cantidad'];
                
                // Opcional: Si el stock llega a 0, cambiar estado
               if ($producto->stock == 0) {
                     $producto->estado = 'agotado';
}
                
                $producto->save();
            }

            // 4. Si todo salió bien, confirmar la transacción
            DB::commit();

            // Devolvemos una respuesta JSON de éxito
            return response()->json([
                'success' => true,
                'message' => '¡Venta registrada exitosamente!',
                'redirect_url' => route('ventas.mostrar') // Redirigir al historial de ventas
            ]);

        } catch (Throwable $e) {
            // 5. Si algo falló, revertir todo
            DB::rollBack();

            // Devolver una respuesta JSON de error
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() // 'Stock insuficiente...'
            ], 422); // 422 es "Unprocessable Entity", bueno para errores de validación
        }
    }

    /**
     * Muestra el historial de ventas (placeholder).
     */
    public function mostrar()
    {
        // Aquí iría la lógica para mostrar el historial de ventas
        // Por ahora, solo creamos una vista simple
        $ventas = Venta::with('usuario', 'detalles.producto')->latest()->get();
        return view('ventas.mostrar', compact('ventas'));
    }
}

