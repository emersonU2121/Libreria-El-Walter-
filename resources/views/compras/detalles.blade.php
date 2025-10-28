@extends('menu')

@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Detalles de Compra #{{ $compra->idcompra }}</h2>
        <a href="{{ route('compras.mostrar') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Historial de compras
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Información de la Compra</h5>
        </div>
        <div class="card-body">
            <!-- Información de la compra -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="border rounded p-3 bg-light">
                        <h6 class="text-dark fw-semibold">Concepto</h6>
                        <p class="mb-0 text-dark">{{ $compra->concepto }}</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded p-3 bg-light">
                        <h6 class="text-dark fw-semibold">Fecha</h6>
                        <p class="mb-0 text-dark">
                            @if($compra->fecha instanceof \Carbon\Carbon)
                                {{ $compra->fecha->format('d/m/Y') }}
                            @else
                                {{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded p-3 bg-light">
                        <h6 class="text-dark fw-semibold">Registrado por</h6>
                        <p class="mb-0 text-dark">{{ $compra->usuario->nombre ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="border rounded p-3 bg-success text-white">
                        <h6 class="fw-semibold">Total Compra</h6>
                        <h4 class="mb-0">${{ number_format($compra->total, 2) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Detalles de productos -->
            <h6 class="text-dark fw-semibold mb-3">Productos Comprados</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th class="text-dark">Producto</th>
                            <th class="text-dark">Origen/Concepto</th>
                            <th class="text-dark text-center">Unidades</th>
                            <th class="text-dark text-end">Precio Unitario</th>
                            <th class="text-dark text-end">Precio Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compra->detalles as $detalle)
                        <tr>
                            <td class="text-dark">
                                <strong>{{ $detalle->producto->nombre ?? 'Producto no encontrado' }}</strong>
                            </td>
                            <td class="text-dark">{{ $detalle->concepto }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $detalle->unidades }}</span>
                            </td>
                            <td class="text-end text-dark">
                                ${{ number_format($detalle->precio_unitario, 2) }}
                            </td>
                            <td class="text-end">
                                <strong class="text-dark">${{ number_format($detalle->precio_total, 2) }}</strong>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-success">
                        <tr>
                            <td colspan="4" class="text-end fw-semibold text-dark">Total General:</td>
                            <td class="text-end fw-semibold text-dark">${{ number_format($compra->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection