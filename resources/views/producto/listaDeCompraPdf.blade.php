<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Lista de Compras</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-size: 13px; }
        .text-center { text-align: center; }
        .text-danger { color: #d9534f; font-weight: bold; }
        .col-check { width: 40px; text-align: center; }
        .checkbox { display: inline-block; width: 16px; height: 16px; border: 1px solid #000; background-color: #fff; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Librería El Walter</h1>
        <p>Lista de Compras</p>
        <p>Fecha de generación: {{ $fecha }}</p>
    </div>

    @if($productos->isEmpty())
        <p style="text-align: center; font-size: 16px;">No se seleccionaron productos.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th class="col-check">OK</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th class="text-center">Existencias</th>
                    <th>Precio Compra (Ref.)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                <tr>
                    <td class="col-check"><span class="checkbox"></span></td>
                    <td>{{ $producto->idproducto }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td class="text-center text-danger">{{ $producto->stock }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
               </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>