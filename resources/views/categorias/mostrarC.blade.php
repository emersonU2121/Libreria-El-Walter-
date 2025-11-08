@extends('menu')

{{-- 
  Esta página no necesita un CSS externo.
  Usará el CSS de 'menu.css' y el de Bootstrap.
--}}

@section('contenido')

<meta name="categorias-validate-url" content="{{ route('categorias.validar-nombre') }}">


<div class="container-fluid py-4 mt-5 px-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Gestión de Categorías</h2>
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
    
    {{-- Muestra errores de registro, pero no los del modal --}}
    @if ($errors->any() && !$errors->has('nombre')) {{-- Asumiendo que el error del modal NO se llama 'nombre' --}}
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
                    <h5 class="mb-0 text-dark">Registrar Nueva Categoría</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('categorias.store') }}" method="post" autocomplete="off" novalidate id="formRegistro">
                        @csrf
                
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-semibold text-dark">Nombre de la Categoría</label>
                            <input type="text" id="nombre" name="nombre" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   value="{{ old('nombre') }}" required>
                            
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            {{-- Div para el error de JS --}}
                            <div id="error-js-registro-cat" class="invalid-feedback d-none">Este campo es requerido.</div> 
                        </div>
                
                        <div class="d-flex justify-content-end gap-2">
                             {{-- Botones adaptados --}}
                            <a href="{{ route('categorias.mostrarC') }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Categoría</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-dark">Categorías Registradas</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('categorias.mostrarC') }}" method="GET" class="row g-3 align-items-center mb-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="q"
                                    class="form-control"
                                    placeholder="Buscar categorías por nombre..."
                                    value="{{ request('q') }}"
                                    aria-label="Buscar categorías">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if(request('q'))
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-2">Resultados para: "{{ request('q') }}"</span>
                                    <a href="{{ route('categorias.mostrarC') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                                </div>
                            @endif
                        </div>
                    </form>

                    @if($categorias->isEmpty())
                        <div class="alert alert-warning text-center">No hay categorías registradas.</div>
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
                                @foreach($categorias as $c)
                                    <tr>
                                        <td>{{ $c->nombre }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                {{-- Editar (Botón con nuevo estilo 'outline') --}}
                                                <button
                                                  type="button"
                                                  class="btn btn-sm btn-outline-primary btn-open-edit"
                                                  data-bs-toggle="modal"
                                                  data-bs-target="#modalEditar"
                                                  data-idcategoria="{{ $c->idcategoria }}"
                                                  data-nombre="{{ $c->nombre }}"
                                                  data-update-url="{{ route('categorias.update', $c->idcategoria) }}"
                                                >Editar</button>
                        
                                                {{-- Eliminar (Botón con nuevo estilo 'outline') --}}
                                                <button
                                                  type="button"
                                                  class="btn btn-sm btn-outline-danger btn-open-eliminar"
                                                  data-bs-toggle="modal"
                                                  data-bs-target="#modalEliminar"
                                                  data-idcategoria="{{ $c->idcategoria }}"
                                                  data-nombre="{{ $c->nombre }}"
                                                  data-delete-url="{{ route('categorias.destroy', $c->idcategoria) }}"
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
                                Mostrando {{ $categorias->firstItem() }}–{{ $categorias->lastItem() }} de {{ $categorias->total() }}
                            </div>
                            <div>
                                {!! $categorias->links('vendor.pagination.prev-next-only') !!}
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
@include('categorias._modal_editar')
@include('categorias._modal_baja') 

@endsection


{{-- 
    Scripts (Todo tu código JS original, sin cambios, 
    movido dentro de un solo @push) 
--}}
@push('scripts')
<script src="{{ asset('js/categorias/mostrarC.js') }}"></script>
@endpush