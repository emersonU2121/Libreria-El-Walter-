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
        .resumen { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
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

    <div class="resumen">
        <strong>Resumen del Mes:</strong><br>
        Total de Ventas: {{ $ventas->count() }}<br>
        Ingresos Totales: ${{ number_format($totalMes, 2) }}<br>
        Período: {{ ucfirst($mes) }} {{ $año }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th># Venta</th>
                <th>N° Factura</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $currentPage = 1;
                $totalPages = ceil($ventas->count() / 25);
            @endphp
            
            @foreach($ventas as $index => $venta)
            <tr>
                <td>{{ $venta->idventa }}</td>
                <td>
                    @if(!empty($venta->numero_factura))
                        {{ $venta->numero_factura }}
                    @else
                        <span style="color: #666; font-style: italic;">Sin factura</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                <td>{{ $venta->usuario_nombre ?? '—' }}</td>
                <td class="text-right">${{ number_format($venta->total, 2) }}</td>
            </tr>
            
            <!-- ✅ PAGINACIÓN: Salto de página cada 25 registros -->
            @if(($index + 1) % 25 == 0 && !$loop->last)
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
                <th># Venta</th>
                <th>N° Factura</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @endif
            @endforeach
            
            @if($ventas->count() > 0)
            <tr style="background-color: #e9ecef; font-weight: bold;">
                <td colspan="4" class="text-right">TOTAL DEL MES:</td>
                <td class="text-right">${{ number_format($totalMes, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Mostrar paginación en la última página -->
    <div class="pagination">
        Página {{ $currentPage }} de {{ $totalPages }}
    </div>

    @if($ventas->isEmpty())
    <div style="text-align: center; padding: 20px; color: #666;">
        No hay ventas registradas para este mes.
    </div>
    @endif

    <div class="footer">
        Total de ventas en el período: {{ $ventas->count() }}
    </div>
</body>
</html>