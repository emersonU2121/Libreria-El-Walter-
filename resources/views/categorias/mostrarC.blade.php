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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const $ = (sel) => document.querySelector(sel);

  // Resalta el elemento del paso
  function focusStep(el) {
    if (!el) return false;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    el.classList.add('help-pulse');
    setTimeout(() => el.classList.remove('help-pulse'), 1400);
    return true;
  }

  const btnAyuda = document.getElementById('btn-ayuda');
  if (!btnAyuda) return;

  btnAyuda.addEventListener('click', async () => {
    let i = 0;
    const steps = [
      {
        icon: 'question',
        title: 'Ayuda rápida',
        html: `
          <div class="text-start">
            <p>En esta pantalla puedes <b>registrar, buscar, editar y eliminar</b> categorías.</p>
            <ul class="mb-0">
              <li>El panel <b>izquierdo</b> registra nuevas categorías.</li>
              <li>El panel <b>derecho</b> lista y permite buscar/accionar.</li>
            </ul>
          </div>`
      },
      {
        icon: 'info',
        title: 'Registrar nueva categoría',
        html: `
          <div class="text-start">
            <ol class="mb-0">
              <li>Escribe el <b>Nombre de la Categoría</b>.</li>
              <li>Haz clic en <b>Registrar Categoría</b>.</li>
            </ol>
          </div>`,
        onOpen: () => focusStep($('#categoria_nombre')) || focusStep($('#btn-registrar-categoria'))
      },
      {
        icon: 'info',
        title: 'Buscar categorías',
        html: `
          <div class="text-start">
            <ol class="mb-0">
              <li>Escribe el nombre en <b>Buscar categorías por nombre</b>.</li>
              <li>Presiona <b>Buscar</b>.</li>
            </ol>
          </div>`,
        onOpen: () => focusStep($('#input-buscar-categoria')) || focusStep($('#btn-buscar-categoria'))
      },
      {
        icon: 'info',
        title: 'Lista y acciones',
        html: `
          <div class="text-start">
            <p>En cada fila puedes:</p>
            <ul class="mb-2">
              <li><b>Editar</b> el nombre de la categoría.</li>
              <li><b>Eliminar</b> (se pedirá confirmación).</li>
            </ul>
            <p class="mb-0"><small>Si no ves acciones, revisa tus permisos.</small></p>
          </div>`,
        onOpen: () => focusStep($('#tabla-categorias'))
      }
    ];

    const modal = Swal.mixin({
      showCancelButton: false,
      focusConfirm: true,
      confirmButtonText: 'Siguiente',
      confirmButtonColor: '#3085d6',
      width: 600,
      allowOutsideClick: false,
      didOpen: () => { const s = steps[i]; if (s && typeof s.onOpen === 'function') setTimeout(s.onOpen, 50); }
    });

    for (i = 0; i < steps.length; i++) {
      await modal.fire({
        icon: steps[i].icon,
        title: steps[i].title,
        html: steps[i].html,
        confirmButtonText: i === steps.length - 1 ? 'Entendido' : 'Siguiente'
      });
    }
  });
});
</script>

<style>
/* Efecto de resalte */
.help-pulse {
  box-shadow: 0 0 0 0 rgba(49,132,253,.5);
  animation: help-pulse 1.4s ease-out 1;
  outline: 2px solid rgba(49,132,253,.35);
  border-radius: 6px;
}
@keyframes help-pulse {
  0%   { box-shadow: 0 0 0 0 rgba(49,132,253,.5); }
  70%  { box-shadow: 0 0 0 12px rgba(49,132,253,0); }
  100% { box-shadow: 0 0 0 0 rgba(49,132,253,0); }
}
</style>
@endpush
