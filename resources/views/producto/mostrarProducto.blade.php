@extends('menu') 

@section('contenido')

<div class="container-fluid py-4 mt-5 px-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 text-dark">Lista de Productos</h2>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('productos.registrar') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Registrar Producto
        </a>
        <a href="{{ route('productos.reporte.stock_bajo.pdf', ['umbral' => 5]) }}" class="btn btn-outline-secondary">
            <i class="fas fa-file-pdf me-2"></i>Stock bajo (PDF)
        </a>
    </div>
</div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Productos Registrados</h5>
        </div>

        <div class="card-body">
            @if(session('warn_low'))   <div class="alert alert-warning">{{ session('warn_low') }}</div> @endif
            @if(session('warn_zero'))  <div class="alert alert-danger">{{ session('warn_zero') }}</div> @endif
            @if(session('ok'))        <div class="alert alert-success">{{ session('ok') }}</div> @endif
            @if(session('error'))     <div class="alert alert-danger">{{ session('error') }}</div> @endif

            @php
                $umbral = $umbralStock ?? 5;
                $bajos = $productos->filter(fn($pr) => ($pr->stock ?? 0) > 0 && ($pr->stock ?? 0) <= $umbral);
            @endphp
            @if($bajos->count() > 0)
            <div class="alert alert-warning mb-4">
                <strong>Atención:</strong> {{ $bajos->count() }} producto(s) con stock ≤ {{ $umbral }}.
                @foreach($bajos->take(5) as $b)
                <span class="badge bg-warning text-dark ms-1">{{ $b->nombre }} ({{ $b->stock }})</span>
                @endforeach
                @if($bajos->count() > 5) <span class="ms-1">…</span> @endif
            </div>
            @endif

            <form action="{{ route('productos.mostrar') }}" method="GET" class="row g-3 align-items-center mb-4">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" 
                               name="buscar" 
                               class="form-control" 
                               placeholder="Buscar productos por nombre, ID, marca, categoría..." 
                               value="{{ request('buscar') }}"
                               aria-label="Buscar productos">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    @if(request('buscar'))
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">
                            Resultados para: "{{ request('buscar') }}"
                        </span>
                        <a href="{{ route('productos.mostrar') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                    @endif
                </div>
            </form>

            @if($productos->isEmpty())
                <div class="alert alert-info text-center">No hay productos registrados.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-dark">Identificador</th>
                                <th class="text-dark">Imagen</th>
                                <th class="text-dark">Nombre</th>
                                <th class="text-dark">Precio de compra</th>
                                <th class="text-dark">Precio de venta</th>
                                <th class="text-dark">Existencias</th>
                                <th class="text-dark text-center">Estado</th>
                                <th class="text-dark">Marca</th>
                                <th class="text-dark">Categoría</th>
                                <th class="text-dark text-center" style="width:260px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($productos as $p)
                            @php
                                $estadoBD = $p->estado ?? 'disponible';
                                $stockVal = (int)($p->stock ?? 0);
                                $estadoVisual = 'disponible';
                                if ($stockVal === 0)       $estadoVisual = 'agotado';
                                elseif ($estadoBD === 'agotado') $estadoVisual = 'inactivo';
                                $esActivo = ($estadoBD === 'disponible');
                                $esBajo   = $stockVal > 0 && $stockVal <= $umbral;
                                $src = $p->imagen ? asset('storage/'.$p->imagen) : asset('images/no-image.png');
                            @endphp
                            <tr class="{{ $esBajo ? 'table-warning' : '' }}">
                                <td>{{ $p->idproducto }}</td>
                                <td>
                                    <img src="{{ $src }}" alt="img {{ $p->nombre }}"
                                         style="height:50px;width:auto;border:1px solid #eee;padding:2px;border-radius:4px;">
                                </td>
                                <td>{{ $p->nombre }}</td>
                                <td>${{ number_format($p->precio,2) }}</td>
                                <td>${{ number_format($p->precio_venta,2) }}</td>
                                <td>
                                    {{ $p->stock }}
                                    @if($stockVal === 0)
                                    <span class="badge bg-danger ms-1">Reabastecer</span>
                                    @elseif($esBajo)
                                    <span class="badge bg-warning text-dark ms-1">Bajo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($estadoVisual === 'disponible')
                                    <span class="badge bg-success">disponible</span>
                                    @elseif($estadoVisual === 'agotado')
                                    <span class="badge bg-warning text-dark">agotado</span>
                                    @else
                                    <span class="badge bg-secondary">inactivo</span>
                                    @endif
                                </td>
                                <td>{{ $p->marca_nombre ?? '—' }}</td>
                                <td>{{ $p->categoria_nombre ?? '—' }}</td>
                                <td class="actions-cell">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button"
                                            class="btn btn-sm btn-outline-primary btn-open-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditarProducto"
                                            data-idproducto="{{ $p->idproducto }}"
                                            data-nombre="{{ $p->nombre }}"
                                            data-precio="{{ $p->precio }}"
                                            data-precio_venta="{{ $p->precio_venta }}"
                                            data-stock="{{ $p->stock }}"
                                            data-estado="{{ $p->estado }}"
                                            data-idmarca="{{ $p->idmarca }}"
                                            data-idcategoria="{{ $p->idcategoria }}"
                                            data-imagen="{{ $p->imagen }}"
                                            data-update-url="{{ route('productos.update', $p->idproducto) }}"
                                            {{ $esActivo ? '' : 'disabled' }}
                                            title="{{ $esActivo ? 'Editar' : 'Producto inactivo: reactívalo para editar' }}"
                                        >Editar</button>

                                        <button type="button"
                                            class="btn btn-sm {{ $esActivo ? 'btn-outline-warning' : 'btn-outline-success' }} btn-open-baja"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalBajaProducto"
                                            data-idproducto="{{ $p->idproducto }}"
                                            data-nombre="{{ $p->nombre }}"
                                            data-estado-bd="{{ $estadoBD }}"
                                            data-activo="{{ $esActivo ? 1 : 0 }}"
                                            data-inactivar-url="{{ route('productos.inactivo', $p->idproducto) }}"
                                            data-activar-url="{{ route('productos.activo', $p->idproducto) }}"
                                        >{{ $esActivo ? 'Dar de baja' : 'Reactivar' }}</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Mostrando {{ $productos->firstItem() }}–{{ $productos->lastItem() }} de {{ $productos->total() }}
                    </div>
                    <div>
                        {!! $productos->links('vendor.pagination.prev-next-only') !!}
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>
<button type="button" 
        class="btn btn-primary shadow" 
        id="btn-ayuda" 
        style="
            position: fixed; 
            bottom: 20px; 
            right: 20px; 
            z-index: 1050;
            width: 50px;         
            height: 50px;        
            border-radius: 50%;  
            font-size: 1.5rem;  
            font-weight: bold;   
            padding: 0;          
        ">
    ?
</button>

@include('producto._modal_editar_producto')
@include('producto._modal_baja_producto')

{{-- Estilos para que el color de fila 'table-warning' se aplique --}}
<style>
.table tr[class*="table-"] > td,
.table tr[class*s="table-"] > th {
    background-color: inherit !important;
}
</style>

@push('scripts')
<script src="{{ asset('js/producto/mostrarProducto.js') }}"></script>
@endpush
@endsection