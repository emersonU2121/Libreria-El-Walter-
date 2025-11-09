@extends('menu')

@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">
    
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Historial de Ventas</h2>
        <a href="{{ route('ventas.registrar') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nueva Venta
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Ventas Registradas</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($ventas->isEmpty())
                <div class="alert alert-info text-center">No hay ventas registradas.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th class="text-dark">ID Venta</th>
                                <th class="text-dark">Fecha</th>
                                <th class="text-dark">Usuario</th>
                                <th class="text-dark">Total</th>
                                <th class="text-dark">Items</th>
                                {{-- <th class="text-dark">Acciones</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ventas as $venta)
                                <tr>
                                    <td>{{ $venta->idventa }}</td>
                                    <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $venta->usuario->nombre ?? 'N/A' }}</td>
                                    <td><strong class="text-success">${{ number_format($venta->total, 2) }}</strong></td>
                                    <td>{{ $venta->detalles->sum('unidades') }}</td>
                                    {{-- <td><a href="#" class="btn btn-sm btn-outline-primary">Ver Detalles</a></td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection