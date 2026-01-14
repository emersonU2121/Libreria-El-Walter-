<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
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

        // 1. Validar stock
        foreach ($carrito as $item) {
            $producto = Producto::find($item['id']);
            if ($producto->stock < $item['cantidad']) {
                throw new \Exception('Stock insuficiente para el producto: ' . $producto->nombre);
            }
        }

        // 2. Generar número de factura
        $fechaHoy = now()->toDateString();
        $contadorHoy = Venta::whereDate('fecha', $fechaHoy)->count() + 1;
        $numeroFactura = sprintf('FAC-%s-%04d', now()->format('Ymd'), $contadorHoy);

        // 3. Crear la venta
        $venta = Venta::create([
            'idusuario'       => $usuario->idusuario,
            'fecha'           => now(),
            'total'           => $totalVenta,
            'numero_factura'  => $numeroFactura,
        ]);

        // 4. Crear los detalles y actualizar stock
        foreach ($carrito as $item) {
            DetalleVenta::create([
                'idventa' => $venta->idventa,
                'idproducto' => $item['id'],
                'unidades' => $item['cantidad'],
                'precio_total' => $item['cantidad'] * $item['precio_venta'],
            ]);

            $producto = Producto::find($item['id']);
            $producto->stock -= $item['cantidad'];
            if ($producto->stock == 0) {
                $producto->estado = 'agotado';
            }
            $producto->save();
        }

        DB::commit();

        return response()->json([
            'ok'              => true,
            'venta_id'        => $venta->idventa,
            'numero_factura'  => $venta->numero_factura,
            'total'           => $venta->total,
            'pdf'             => route('ventas.detalle.pdf', ['id' => $venta->idventa]),
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();

        return response()->json([
            'ok' => false,
            'message' => $e->getMessage(),
        ], 422);
    }
}


    /**
     * Muestra el historial de ventas (placeholder).
     */
    public function mostrar(Request $request)
{
    $perPage = 10; // tamaño de página

    $q = Venta::with(['usuario','detalles'])
        ->orderByDesc('fecha');

    // Filtros (opcionales)
    if ($request->filled('desde') || $request->filled('hasta')) {
        $desde = $request->filled('desde')
            ? Carbon::createFromFormat('Y-m-d', $request->desde)->startOfDay()
            : Carbon::minValue();
        $hasta = $request->filled('hasta')
            ? Carbon::createFromFormat('Y-m-d', $request->hasta)->endOfDay()
            : Carbon::maxValue();

        $q->whereBetween('fecha', [$desde, $hasta]);
    }

    // Paginar y conservar filtros en la URL
    $ventas = $q->paginate($perPage)
                ->appends($request->only('desde','hasta'));

    return view('ventas.mostrar', compact('ventas'));
}

    public function show($idventa)
{
    $venta = \App\Models\Venta::with(['usuario', 'detalles.producto'])
              ->where('idventa', $idventa)
              ->firstOrFail();

    return view('ventas.detalles', compact('venta'));
}

public function pdf($idventa)
{
    $venta = \App\Models\Venta::with(['usuario'])
             ->where('idventa', $idventa)->firstOrFail();

    // Datos fijos de la empresa (según tu indicación)
    $empresa = [
        'nombre_fiscal' => 'Librería "El Walter"',
        'direccion'     => 'Cuscatlán, Cojutepeque, Colonia Cuscatlán, El Walter',
        'logo_url'      => null, // no hay logo
    ];

    // Configuración (sin IVA)
    $config = [
        'moneda_simbolo' => '$',
        'aplica_iva'     => false,
        'iva_tasa'       => 0.00,
    ];

    // Cliente por defecto (no manejas clientes)
    $cliente = [
        'nombre'    => 'Consumidor Final',
        'documento' => 'DUI: 00000000-0',
        'direccion' => '',
        'telefono'  => '',
    ];

    $pdf = Pdf::loadView('ventas.factura', compact('venta','empresa','config','cliente'))
              ->setPaper('a4', 'portrait');

    return $pdf->stream("Factura-{$venta->numero_factura}.pdf");
}

}