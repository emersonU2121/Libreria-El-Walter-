@extends('menu')

{{-- Esta página no necesita un CSS personalizado, usará el de Bootstrap y menu.css --}}

@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Gestión de Marcas</h2>
    </div>

    @if(session('ok'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('ok') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="row">
        
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-dark">Registrar Nueva Marca</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('marcas.store') }}" method="post" autocomplete="off" novalidate id="formRegistro">
                        @csrf
                
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold text-dark">Nombre de la Marca</label>
                            <input type="text" id="nombre" name="nombre" class="form-control"
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                
                        <div class="d-flex justify-content-end gap-2">
                             {{-- Botones adaptados, pero con tus rutas originales --}}
                            <a href="{{ route('marcas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Marca</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-dark">Marcas Registradas</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('marcas.mostrar') }}" method="GET" class="row g-3 align-items-center mb-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" 
                                       name="buscar" 
                                       class="form-control" 
                                       placeholder="Buscar marcas por nombre..." 
                                       value="{{ request('buscar') }}"
                                       aria-label="Buscar marcas">
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
                                    <a href="{{ route('marcas.mostrar') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> Limpiar
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>

                    @if($marcas->isEmpty())
                        <div class="alert alert-warning text-center">No hay marcas registradas.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-sm text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-dark">Nombre</th>
                                        <th class="text-dark" style="width:240px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($marcas as $m)
                                    <tr>
                                        <td>{{ $m->nombre }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                {{-- Editar (Botón adaptado a 'outline') --}}
                                                <button
                                                  type="button"
                                                  class="btn btn-sm btn-outline-primary btn-open-edit"
                                                  data-bs-toggle="modal"
                                                  data-bs-target="#modalEditar"
                                                  data-idmarca="{{ $m->idmarca }}"
                                                  data-nombre="{{ $m->nombre }}"
                                                  data-update-url="{{ route('marcas.update', $m->idmarca) }}"
                                                >Editar</button>
                        
                                                {{-- Eliminar (Botón adaptado a 'outline') --}}
                                                <button
                                                  type="button"
                                                  class="btn btn-sm btn-outline-danger btn-open-eliminar"
                                                  data-bs-toggle="modal"
                                                  data-bs-target="#modalEliminar"
                                                  data-idmarca="{{ $m->idmarca }}"
                                                  data-nombre="{{ $m->nombre }}"
                                                  data-delete-url="{{ route('marcas.destroy', $m->idmarca) }}"
                                                >Eliminar</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                Mostrando {{ $marcas->firstItem() }}–{{ $marcas->lastItem() }} de {{ $marcas->total() }}
                            </div>
                            <div>
                                {!! $marcas->links('vendor.pagination.prev-next-only') !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div> </div> 

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
@include('marcas._modal_editar_marca')
@include('marcas._modal_baja_marca') 

@endsection


@push('scripts')
   
    <script src="{{ asset('js/marcas/mostrar_marcas.js') }}"></script>

   
    @if ($errors->has('nombre'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
            modal.show();
        });
    </script>
    @endif
@endpush