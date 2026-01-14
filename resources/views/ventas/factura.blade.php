@php
  $simbolo = $config['moneda_simbolo'] ?? '$';
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Factura {{ $venta->numero_factura }}</title>
  <style>
    @page { margin: 26mm 18mm 22mm 18mm; }
    body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size: 12px; color: #222; }
    .row{ display:flex; gap:16px; }
    .col{ flex:1; }
    .text-right{ text-align:right; } .text-center{ text-align:center; }
    .mb-0{ margin-bottom:0; } .mb-1{ margin-bottom:4px; } .mb-2{ margin-bottom:8px; } .mb-3{ margin-bottom:12px; }
    .fw-bold{ font-weight:700; } .muted{ color:#666; }
    .box{ border:1px solid #ddd; border-radius:6px; padding:10px 12px; margin-bottom:12px; }
    table{ width:100%; border-collapse:collapse; }
    th,td{ padding:8px; }
    thead th{ background:#f3f4f6; border-bottom:1px solid #ddd; text-align:left; font-weight:700; }
    tbody td{ border-bottom:1px solid #eee; }
    .totals td{ padding:4px 8px; }
    .tag{ display:inline-block; padding:3px 8px; background:#eef2ff; color:#3730a3; border-radius:999px; font-size:11px; }
    .title{ font-size:20px; margin:0; }
  </style>
</head>
<body>

  <!-- Encabezado -->
  <div class="row mb-2">
    <div class="col">
      <h1 class="title">{{ $empresa['nombre_fiscal'] }}</h1>
      <p class="mb-0">{{ $empresa['direccion'] }}</p>
    </div>
    <div class="col text-right">
      <span class="tag">FACTURA</span>
      <h2 class="mb-1">No. {{ $venta->numero_factura }}</h2>
      <div class="muted">Fecha: {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</div>
      <div class="muted">Atendió: {{ $venta->usuario->nombre ?? '—' }}</div>
    </div>
  </div>

  <!-- Cliente / Resumen -->
  <div class="row">
    <div class="col box">
      <div class="fw-bold mb-1">Cliente</div>
      <div class="mb-0">{{ $cliente['nombre'] }}</div>
      <div class="muted mb-0">{{ $cliente['documento'] }}</div>
    </div>
    <div class="col box">
      <div class="fw-bold mb-1">Resumen</div>
      <div class="muted mb-0">Ítems: {{ $venta->detalles->sum('unidades') }}</div>
      <div class="muted mb-0">Moneda: {{ $simbolo }}</div>
      <div class="muted mb-0">Condición: Contado</div>
    </div>
  </div>

  <!-- Items -->
  <div class="box">
    <table>
      <thead>
        <tr>
          <th style="width:48%">Producto</th>
          <th style="width:12%">Unidades</th>
          <th style="width:20%">Precio Unitario</th>
          <th style="width:20%">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach($venta->detalles as $d)
          @php $sub = $d->unidades * $d->precio_unitario; @endphp
          <tr>
            <td>
              <div>{{ $d->producto->nombre ?? '—' }}</div>
            </td>
            <td>{{ $d->unidades }}</td>
            <td>{{ $simbolo }}{{ number_format($d->precio_unitario,2) }}</td>
            <td>{{ $simbolo }}{{ number_format($sub,2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <!-- Totales -->
  <div class="row">
    <div class="col"></div>
    <div class="col box">
      <table class="totals">
        <tr>
          <td class="text-right fw-bold">Total a pagar:</td>
          <td class="text-right fw-bold">{{ $simbolo }}{{ number_format($venta->total,2) }}</td>
        </tr>
      </table>
    </div>
  </div>

  <div class="muted" style="margin-top:10px; font-size:11px;">
    Librería "El Walter" — Documento generado automáticamente. Gracias por su compra.
  </div>
</body>
</html>
