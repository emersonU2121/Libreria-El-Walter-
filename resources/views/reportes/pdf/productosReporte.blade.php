<!-- resources/views/reportes/pdf/productosReporte.blade.php -->
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
                <th>Identificador</th>
                <th>Nombre</th>
                <th class="text-right">Precio Compra</th>
                <th class="text-right">Precio Venta</th>
                <th>Existencias</th>
                <th>Estado</th>
                <th>Marca</th>
                <th>Categoría</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->idproducto }}</td>
                <td>{{ $producto->nombre }}</td>
                <td class="text-right">${{ number_format($producto->precio, 2) }}</td>
                <td class="text-right">${{ number_format($producto->precio_venta, 2) }}</td>
                <td>{{ $producto->stock }}</td>
                <td>{{ $producto->estado }}</td>
                <td>{{ $producto->marca_nombre ?? '—' }}</td>
                <td>{{ $producto->categoria_nombre ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total de productos: {{ $productos->count() }}
    </div>
    <footer>
  <table style="width:100%;">
  </table>
  <script type="text/php">
    if (isset($pdf)) {
      $pdf->page_text(520, 818, "Página {PAGE_NUM} de {PAGE_COUNT}",
        $fontMetrics->get_font("DejaVu Sans","normal"), 9, [0.4,0.4,0.4]);
    }
  </script>
</footer>
</body>
</html>