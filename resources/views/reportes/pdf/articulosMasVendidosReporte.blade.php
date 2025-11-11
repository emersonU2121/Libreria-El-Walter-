<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .title { font-size: 24px; font-weight: bold; color: #333; }
        .subtitle { font-size: 16px; color: #666; }
        .libreria { font-size: 18px; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background-color: #f8f9fa; border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table td { border: 1px solid #ddd; padding: 6px; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #666; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .page-break { page-break-after: always; }
        
        /* Estilo para la paginación */
        .pagination {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
            color: #666;
            background-color: white;
            padding: 5px 10px;
            border-radius: 3px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="libreria">Librería El Walter</div>
        <div class="title">{{ $titulo }}</div>
        <div class="subtitle">Generado: {{ $fecha }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>ID Producto</th>
                <th>Producto</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-center">Unidades Vendidas</th>
                <th class="text-right">Total Generado</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentPage = 1;
                $totalPages = ceil(count($articulos) / 20);
            @endphp
            
            @foreach($articulos as $index => $articulo)
            <tr>
                <td class="text-center"><strong>{{ $index + 1 }}°</strong></td>
                <td>{{ $articulo->idproducto }}</td>
                <td>{{ $articulo->producto_nombre }}</td>
                <td class="text-right">${{ number_format($articulo->precio_venta, 2) }}</td>
                <td class="text-center">{{ $articulo->total_vendido }}</td>
                <td class="text-right">${{ number_format($articulo->total_ingresos, 2) }}</td>
            </tr>
            
            <!-- ✅ PAGINACIÓN: Salto de página cada 20 registros -->
            @if(($index + 1) % 20 == 0 && !$loop->last)
                @php $currentPage++; @endphp
        </tbody>
    </table>
    
    <!-- Mostrar paginación en la esquina inferior -->
    <div class="pagination">
        Página {{ $currentPage }} de {{ $totalPages }}
    </div>
    
    <!-- Salto de página -->
    <div style="page-break-after: always;"></div>
    
    <!-- Encabezado repetido en nueva página -->
    <div class="header">
        <div class="libreria">Librería El Walter</div>
        <div class="title">{{ $titulo }} (Continuación)</div>
        <div class="subtitle">Generado: {{ $fecha }}</div>
    </div>
    
    <table class="table">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th>ID Producto</th>
                <th>Producto</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-center">Unidades Vendidas</th>
                <th class="text-right">Total Generado</th>
            </tr>
        </thead>
        <tbody>
            @endif
            @endforeach
        </tbody>
    </table>

    <!-- Mostrar paginación en la última página -->
    <div class="pagination">
        Página {{ $currentPage }} de {{ $totalPages }}
    </div>

    @if($articulos->isEmpty())
    <div style="text-align: center; padding: 20px; color: #666;">
        No hay datos de ventas para el período seleccionado.
    </div>
    @else
    <div style="margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
        <strong>Resumen Estadístico:</strong><br>
        • Producto más vendido: <strong>{{ $articulos->first()->producto_nombre }}</strong> ({{ $articulos->first()->total_vendido }} unidades)<br>
        • Total de unidades vendidas: <strong>{{ $articulos->sum('total_vendido') }}</strong><br>
        • Ingresos totales del top {{ $articulos->count() }}: <strong>${{ number_format($articulos->sum('total_ingresos'), 2) }}</strong>
    </div>
    @endif

    <div class="footer">
        Período analizado: {{ $periodo }}<br>
        Total de productos en el ranking: {{ $articulos->count() }}
    </div>
</body>
</html>