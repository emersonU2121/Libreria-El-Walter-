<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Compra; // ajusta si tu modelo se llama distinto

use Illuminate\Http\Request;

class CompraReporteController extends Controller
{
    public function detallePdf(Compra $compra)
    {
        // Asegura las relaciones que necesitas en la vista
       $compra->load([
    'usuario:idusuario,nombre',   // ← cambia 'id' por 'idusuario'
    'detalles.producto:idproducto,nombre' // (aquí deja 'id' solo si la tabla productos SÍ tiene 'id')
]);

        $data = [
            'empresa'  => 'Librería "El Walter"',
            'compra'   => $compra,
            'generado' => now()->format('d/m/Y H:i'),
        ];

        $pdf = Pdf::loadView('compras.reporte_detalle_pdf', $data)
                  ->setPaper('a4', 'portrait');

        return $pdf->download("compra_{$compra->idcompra}.pdf");
    }
}
