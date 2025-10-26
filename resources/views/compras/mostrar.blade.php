@extends('menu')

@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Historial de Compras</h2>
        <a href="{{ route('compras.registrar') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva Compra
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Lista de Compras Registradas</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($compras->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="table-light">
                        <tr>
                            <th class="text-dark">Fecha</th>
                            <th class="text-dark">Productos</th>
                            <th class="text-dark">Orígenes</th>
                            <th class="text-dark">Unidades</th>
                            <th class="text-dark">Total</th>
                            <th class="text-dark">Usuario</th>
                            <th class="text-dark">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compras as $compra)
                        <tr>
                            <td>
                                @if($compra->fecha instanceof \Carbon\Carbon)
                                    {{ $compra->fecha->format('d/m/Y') }}
                                @else
                                    {{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $compra->cantidad_productos }} productos</span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    @php
                                        $conceptosUnicos = $compra->detalles->pluck('concepto')->unique()->take(2);
                                    @endphp
                                    @foreach($conceptosUnicos as $concepto)
                                        {{ $concepto }}@if(!$loop->last), @endif
                                    @endforeach
                                    @if($compra->detalles->pluck('concepto')->unique()->count() > 2)
                                        <span class="text-primary">+{{ $compra->detalles->pluck('concepto')->unique()->count() - 2 }} más</span>
                                    @endif
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $compra->unidades_totales }}</span>
                            </td>
                            <td>
                                <strong class="text-success">${{ number_format($compra->total, 2) }}</strong>
                            </td>
                            <td>{{ $compra->usuario->nombre ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('compras.detalles', $compra->idcompra) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No hay compras registradas</h5>
                <p class="text-muted mb-4">Comienza registrando tu primera compra</p>
                <a href="{{ route('compras.registrar') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Registrar Primera Compra
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection