<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Producto; 
class ReporteProductoController extends Controller
{
     public function stockBajoPdf(Request $request)
{
    $umbral = (int)($request->input('umbral', 5));

    $productos = Producto::select('idproducto', 'nombre', 'precio', 'stock')
        ->whereNotNull('stock')
        ->where('stock', '<', $umbral)
        ->orderBy('stock')           // menor stock primero
        ->orderBy('nombre')
        ->get();

    $data = [
        'empresa'   => 'Librería "El Walter"',
        'generado'  => now()->format('d/m/Y H:i'),
        'umbral'    => $umbral,
        'productos' => $productos,
    ];

    $pdf = Pdf::loadView('producto.reporte_stock_bajo_pdf', $data)
              ->setPaper('a4', 'portrait');

    // ✅ Habilitar PHP en Dompdf para que funcione <script type="text/php">
    // Para Dompdf v2.x:
    $pdf->set_option('isPhpEnabled', true);
    // Compatibilidad con versiones previas:
    // $pdf->getDomPDF()->set_option('isPhpEnabled', true);

    return $pdf->download('Lista de compras ' . now()->format('Y-m-d') . '.pdf');
}

}
