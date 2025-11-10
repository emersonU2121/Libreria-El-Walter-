@extends('menu')

@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">

    <!-- ENCABEZADO -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Detalle de Venta</h2>
        <div>
            <a href="{{ route('ventas.mostrar') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Volver al historial
            </a>
            <a href="{{ route('ventas.detalle.pdf', $venta->idventa) }}" class="btn btn-outline-danger" target="_blank">
                <i class="fas fa-file-pdf me-1"></i> Exportar PDF
            </a>
        </div>
    </div>

    <!-- INFORMACIÓN GENERAL -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-dark">Información General</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted">Número de Factura</label>
                    <p class="mb-0">{{ $venta->numero_factura }}</p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted">Fecha</label>
                    <p class="mb-0">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted">Usuario</label>
                    <p class="mb-0">{{ $venta->usuario->nombre ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold text-muted">Total de la Venta</label>
                    <p class="mb-0 text-success fw-bold">${{ number_format($venta->total, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- DETALLE DE PRODUCTOS -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="mb-0 text-dark">Productos Vendidos</h5>
        </div>
        <div class="card-body">
            @if($venta->detalles->isEmpty())
                <div class="alert alert-warning text-center">No se registraron productos en esta venta.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Unidades</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venta->detalles as $detalle)
                                <tr>
                                    <td>{{ $detalle->producto->nombre ?? 'Sin nombre' }}</td>
                                    <td>{{ $detalle->unidades }}</td>
                                    <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                    <td class="text-success fw-bold">
                                        ${{ number_format($detalle->unidades * $detalle->precio_unitario, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end text-dark">Total general:</th>
                                <th class="text-success fw-bold">${{ number_format($venta->total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
