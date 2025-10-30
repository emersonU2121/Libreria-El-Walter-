<?php
// ReporteController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function mostrarReportes()
    {
        // Obtener datos para mostrar en la vista
        $categorias = DB::table('categoria')->count();
        $marcas = DB::table('marca')->count();
        $productos = DB::table('producto')->count();
        $usuarios = DB::table('usuario')->count();

        return view('reportes.mostrarReportes', compact('categorias', 'marcas', 'productos', 'usuarios'));
    }

    public function categoriasReporte()
    {
        $categorias = DB::table('categoria')
            ->select('idcategoria', 'nombre')
            ->orderBy('nombre')
            ->get();

        // Fecha y hora de El Salvador (UTC-6)
        $fechaElSalvador = Carbon::now('America/El_Salvador')->format('d/m/Y H:i:s');

        $data = [
            'titulo' => 'Reporte de Categorías',
            'categorias' => $categorias,
            'fecha' => $fechaElSalvador
        ];

        $pdf = PDF::loadView('reportes.pdf.categoriasReporte', $data);
        $pdf->set_option('isPhpEnabled', true);
        return $pdf->download('reporte_categorias_' . now()->format('Y_m_d') . '.pdf');
    }

    public function marcasReporte()
    {
        $marcas = DB::table('marca')
            ->select('idmarca', 'nombre')
            ->orderBy('nombre')
            ->get();

        // Fecha y hora de El Salvador (UTC-6)
        $fechaElSalvador = Carbon::now('America/El_Salvador')->format('d/m/Y H:i:s');

        $data = [
            'titulo' => 'Reporte de Marcas',
            'marcas' => $marcas,
            'fecha' => $fechaElSalvador
        ];

        $pdf = PDF::loadView('reportes.pdf.marcasReporte', $data);
        $pdf->set_option('isPhpEnabled', true);
        return $pdf->download('reporte_marcas_' . now()->format('Y_m_d') . '.pdf');
    }

    public function productosReporte()
    {
        $productos = DB::table('producto as p')
            ->leftJoin('marca as m', 'm.idmarca', '=', 'p.idmarca')
            ->leftJoin('categoria as c', 'c.idcategoria', '=', 'p.idcategoria')
            ->select(
                'p.idproducto',
                'p.nombre',
                'p.precio',
                'p.precio_venta',
                'p.stock',
                'p.estado',
                'm.nombre as marca_nombre',
                'c.nombre as categoria_nombre'
            )
            ->orderBy('p.nombre')
            ->get();

        // Fecha y hora de El Salvador (UTC-6)
        $fechaElSalvador = Carbon::now('America/El_Salvador')->format('d/m/Y H:i:s');

        $data = [
            'titulo' => 'Reporte de Productos',
            'productos' => $productos,
            'fecha' => $fechaElSalvador
        ];

        $pdf = PDF::loadView('reportes.pdf.productosReporte', $data);
        $pdf->set_option('isPhpEnabled', true);
        return $pdf->download('reporte_productos_' . now()->format('Y_m_d') . '.pdf');
    }

    public function usuariosReporte()
    {
        $usuarios = DB::table('usuario')
            ->select('idusuario', 'nombre', 'correo', 'rol', 'activo')
            ->orderBy('nombre')
            ->get();

        // Fecha y hora de El Salvador (UTC-6)
        $fechaElSalvador = Carbon::now('America/El_Salvador')->format('d/m/Y H:i:s');

        $data = [
            'titulo' => 'Reporte de Usuarios',
            'usuarios' => $usuarios,
            'fecha' => $fechaElSalvador
        ];

        $pdf = PDF::loadView('reportes.pdf.usuariosReporte', $data);
        $pdf->set_option('isPhpEnabled', true);
        return $pdf->download('reporte_usuarios_' . now()->format('Y_m_d') . '.pdf');
    }
}