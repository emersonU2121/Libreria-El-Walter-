<!-- resources/views/reportes/pdf/usuariosReporte.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .title { font-size: 24px; font-weight: bold; color: #333; }
        .subtitle { font-size: 16px; color: #666; }
        .libreria { font-size: 18px; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background-color: #f8f9fa; border: 1px solid #ddd; padding: 10px; text-align: left; }
        .table td { border: 1px solid #ddd; padding: 8px; }
        .footer { margin-top: 30px; text-align: right; font-size: 12px; color: #666; }
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
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>{{ $usuario->nombre }}</td>
                <td>{{ $usuario->correo }}</td>
                <td>{{ $usuario->rol }}</td>
                <td>{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Total de usuarios: {{ $usuarios->count() }}
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