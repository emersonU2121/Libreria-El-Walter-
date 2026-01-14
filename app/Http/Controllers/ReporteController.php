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

         $rangoAños = $this->generarRangoAños();


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

   private function generarRangoAños()
    {
        try {
            // Verificar si la tabla venta existe y tiene datos
            if (DB::getSchemaBuilder()->hasTable('venta')) {
                $primerAño = DB::table('venta')
                    ->whereNotNull('fecha')
                    ->min(DB::raw('YEAR(fecha)'));
                
                if ($primerAño) {
                    $ultimoAño = now()->year;
                    // Crear rango desde el año actual hasta el primer año con ventas
                    return range($ultimoAño, $primerAño);
                }
            }
        } catch (\Exception $e) {
            // Si hay algún error, continuar con el método por defecto
        }

        // ✅ MÉTODO POR DEFECTO: Últimos 10 años desde el actual
        $añoActual = now()->year;
        return range($añoActual, $añoActual - 9);
    }

    public function ventasMesReporte(Request $request)
    {
        // Obtener mes y año de la solicitud, o usar el actual
        $mesSeleccionado = $request->get('mes', now()->month);
        $añoSeleccionado = $request->get('año', now()->year);
        
        // Validar que el mes y año sean válidos
        if (!is_numeric($mesSeleccionado) || $mesSeleccionado < 1 || $mesSeleccionado > 12) {
            $mesSeleccionado = now()->month;
        }
        
        // Validar año razonable (desde 2000 hasta 5 años en el futuro)
        $añoMaximo = now()->year + 5;
        if (!is_numeric($añoSeleccionado) || $añoSeleccionado < 2000 || $añoSeleccionado > $añoMaximo) {
            $añoSeleccionado = now()->year;
        }
        
        $ventas = DB::table('venta as v')
            ->leftJoin('usuario as u', 'u.idusuario', '=', 'v.idusuario')
            ->select('v.*', 'u.nombre as usuario_nombre')
            ->whereMonth('v.fecha', $mesSeleccionado)
            ->whereYear('v.fecha', $añoSeleccionado)
            ->orderBy('v.fecha', 'desc')
            ->get();

        $totalMes = $ventas->sum('total');
        $fechaElSalvador = now()->setTimezone('America/El_Salvador')->format('d/m/Y H:i:s');
        
        // Nombres de meses en español
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $nombreMes = $meses[$mesSeleccionado] ?? 'Desconocido';

        $data = [
            'titulo' => 'Reporte de Ventas - ' . $nombreMes . ' ' . $añoSeleccionado,
            'ventas' => $ventas,
            'totalMes' => $totalMes,
            'mes' => $nombreMes,
            'año' => $añoSeleccionado,
            'fecha' => $fechaElSalvador
        ];


        $pdf = PDF::loadView('reportes.pdf.ventasMesReporte', $data);

        $pdf->setPaper('a4', 'landscape'); // Opcional: cambiar a horizontal si hay muchas columnas
    $pdf->setOption('enable-javascript', true);
    $pdf->setOption('javascript-delay', 5000);
    $pdf->setOption('enable-smart-shrinking', true);
    $pdf->setOption('no-stop-slow-scripts', true);
        return $pdf->download('reporte_ventas_' . strtolower($nombreMes) . '_' . $añoSeleccionado . '.pdf');
    }
public function articulosMasVendidosReporte()
{
    $fechaInicio = now()->subDays(30);
    
    $articulos = DB::table('detalle_venta as dv')
        ->join('producto as p', 'p.idproducto', '=', 'dv.idproducto')
        ->join('venta as v', 'v.idventa', '=', 'dv.idventa')
        ->select(
            'p.idproducto',
            'p.nombre as producto_nombre',
            'p.precio_venta',
            DB::raw('SUM(dv.unidades) as total_vendido'),
            DB::raw('SUM(dv.precio_total) as total_ingresos')
        )
        ->where('v.fecha', '>=', $fechaInicio)
        ->groupBy('p.idproducto', 'p.nombre', 'p.precio_venta')
        ->orderByDesc('total_vendido')
        ->limit(20)
        ->get();

    $fechaElSalvador = now()->setTimezone('America/El_Salvador')->format('d/m/Y H:i:s');

    $data = [
        'titulo' => 'Artículos Más Vendidos (Últimos 30 Días)',
        'articulos' => $articulos,
        'fecha' => $fechaElSalvador,
        'periodo' => 'Últimos 30 días'
    ];

    $pdf = PDF::loadView('reportes.pdf.articulosMasVendidosReporte', $data);

     // ✅ AGREGAR PAGINACIÓN AL PDF
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption('enable-javascript', true);
    $pdf->setOption('javascript-delay', 5000);
    $pdf->setOption('enable-smart-shrinking', true);
    $pdf->setOption('no-stop-slow-scripts', true);
    
    return $pdf->download('reporte_articulos_mas_vendidos_' . now()->format('Y_m_d') . '.pdf');
}
}