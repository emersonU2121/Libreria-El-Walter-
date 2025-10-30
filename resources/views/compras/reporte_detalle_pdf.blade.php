<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Detalle de Compra #{{ $compra->idcompra }} | {{ $empresa }}</title>
<style>
  @page { margin: 28mm 18mm 22mm 18mm; }
  body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #111; }
  header { position: fixed; top: -22mm; left: 0; right: 0; height: 20mm; }
  footer { position: fixed; bottom: -18mm; left: 0; right: 0; height: 16mm; font-size: 10px; color:#666; }

  .brand { font-weight: 700; font-size: 18px; }
  .muted { color:#555; }
  .right { text-align:right; }
  .chip { display:inline-block; padding:6px 10px; border-radius:6px; background:#0a6b3e; color:#fff; font-weight:700; }

  .card { border:1px solid #ddd; border-radius:6px; padding:12px; margin-bottom:12px; }
  .grid { width:100%; border-collapse: collapse; }
  .grid th, .grid td { border:1px solid #ddd; padding:6px 8px; }
  .grid th { background:#f3f6f9; text-align:left; font-weight:700; }
  .text-end { text-align:right; }
  .text-center { text-align:center; }
</style>
</head>
<body>

<header>
  <table style="width:100%;">
    <tr>
      <td class="brand">{{ $empresa }}</td>
      <td class="right muted">Detalle de Compra #{{ $compra->idcompra }}</td>
    </tr>
  </table>
</header>

<footer>
  <table style="width:100%;">
    <tr>
      <td class="muted">Generado: {{ $generado }}</td>
      
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
  <div class="card" style="margin-top:6px;">
    <table style="width:100%;">
      <tr>
        <td><strong>Concepto</strong><br>{{ $compra->concepto }}</td>
        <td><strong>Fecha</strong><br>
          @php
            $f = $compra->fecha instanceof \Carbon\Carbon
                 ? $compra->fecha
                 : \Carbon\Carbon::parse($compra->fecha);
          @endphp
          {{ $f->format('d/m/Y') }}
        </td>
        <td><strong>Registrado por</strong><br>{{ $compra->usuario->nombre ?? 'N/A' }}</td>
        <td class="right"><span class="chip">Total Compra: ${{ number_format($compra->total, 2) }}</span></td>
      </tr>
    </table>
  </div>

  <div class="card">
    <strong>Productos Comprados</strong>
    <table class="grid" style="margin-top:8px;">
      <thead>
        <tr>
          <th style="width:32%;">Producto</th>
          <th style="width:28%;">Origen/Concepto</th>
          <th style="width:10%;" class="text-center">Unidades</th>
          <th style="width:15%;" class="text-end">Precio Unitario</th>
          <th style="width:15%;" class="text-end">Precio Total</th>
        </tr>
      </thead>
      <tbody>
        @foreach($compra->detalles as $detalle)
          <tr>
            <td>{{ $detalle->producto->nombre ?? 'Producto no encontrado' }}</td>
            <td>{{ $detalle->concepto }}</td>
            <td class="text-center">{{ number_format($detalle->unidades, 0) }}</td>
            <td class="text-end">${{ number_format($detalle->precio_unitario, 2) }}</td>
            <td class="text-end"><strong>${{ number_format($detalle->precio_total, 2) }}</strong></td>
          </tr>
        @endforeach
        <tr>
          <td colspan="4" class="text-end"><strong>Total General:</strong></td>
          <td class="text-end"><strong>${{ number_format($compra->total, 2) }}</strong></td>
        </tr>
      </tbody>
    </table>
  </div>
</main>

</body>
</html>
