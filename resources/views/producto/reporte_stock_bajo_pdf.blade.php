<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Lista de compras (Productos con Stock Bajo) | {{ $empresa }}</title>
<style>
  @page { margin: 28mm 18mm 22mm 18mm; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color:#111; }
  header { position: fixed; top: -22mm; left:0; right:0; height:20mm; }
  footer { position: fixed; bottom: -18mm; left:0; right:0; height:16mm; font-size:10px; color:#666; }

  .brand{ font-weight:700; font-size:18px; }
  .muted{ color:#555; }
  .right{ text-align:right; }
  .title{ font-size:16px; font-weight:700; margin:0 0 6px 0; }
  .subtitle{ font-size:12px; color:#555; margin:0 0 10px 0; }
  .grid{ width:100%; border-collapse:collapse; }
  .grid th,.grid td{ border:1px solid #ddd; padding:6px 8px; }
  .grid th{ background:#f3f6f9; text-align:left; font-weight:700; }
  .text-end{ text-align:right; }
</style>
</head>
<body>

<header>
  <table style="width:100%;">
    <tr>
      <td class="brand">{{ $empresa }}</td>
      <td class="right muted">Reporte de Stock Bajo</td>
    </tr>
  </table>
</header>

<footer>
  <table style="width:100%;">
    <tr>
      <td class="muted">Generado: {{ $generado }}</td>
      <td class="right muted">Página: <span class="page-number"></span></td>
    </tr>
  </table>
  <script type="text/php">
    if (isset($pdf)) {
      $pdf->page_text(520, 818, "Página {PAGE_NUM} de {PAGE_COUNT}",
        $fontMetrics->get_font("DejaVu Sans","normal"), 9, [0.4,0.4,0.4]);
    }
  </script>
</footer>

<main>
  <div class="title">Productos con Stock Bajo</div>
  <div class="subtitle">Inventario: menores a {{ $umbral }} unidades</div>

  @if($productos->isEmpty())
    <p class="muted">No se encontraron productos por debajo del limite.</p>
  @else
    <table class="grid">
      <thead>
        <tr>
          <th style="width:10%;">ID</th>
          <th style="width:55%;">Producto</th>
          <th style="width:15%;" class="text-end">Precio (compra)</th>
          <th style="width:20%;" class="text-end">Stock</th>
        </tr>
      </thead>
      <tbody>
        @foreach($productos as $p)
          <tr>
            <td>{{ $p->idproducto }}</td>
            <td>{{ $p->nombre }}</td>
            <td class="text-end">${{ number_format($p->precio, 2) }}</td>
            <td class="text-end">{{ (int)$p->stock }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</main>

</body>
</html>