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

            {{-- ================== Filtros por fecha ================== --}}
            <form method="GET" action="{{ route('compras.mostrar') }}" class="row g-2 align-items-end mb-3">
                <div class="col-auto">
                    <label for="desde" class="form-label mb-0 small text-muted">Desde</label>
                    <input type="date" id="desde" name="desde" class="form-control form-control-sm"
                           value="{{ request('desde') }}">
                </div>
                <div class="col-auto">
                    <label for="hasta" class="form-label mb-0 small text-muted">Hasta</label>
                    <input type="date" id="hasta" name="hasta" class="form-control form-control-sm"
                           value="{{ request('hasta') }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-filter me-1"></i> Filtrar
                    </button>
                    <a href="{{ route('compras.mostrar') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                </div>
            </form>
            {{-- ======================================================== --}}

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

            {{-- ================== Paginación ================== --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">
                    Mostrando {{ $compras->firstItem() }}–{{ $compras->lastItem() }} de {{ $compras->total() }}
                </small>
                {{ $compras->appends(request()->except('page'))->links() }}
            </div>
            {{-- ================================================ --}}

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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const $ = (s) => document.querySelector(s);

  function highlight(el) {
    if (!el) return false;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    el.classList.add('help-pulse');
    setTimeout(() => el.classList.remove('help-pulse'), 1400);
    return true;
  }

  function firstInTable(selector) {
    const t = $('#tabla-historial-compras') || document;
    return t.querySelector(selector);
  }

  const btnAyuda = $('#btn-ayuda');
  if (!btnAyuda) return;

  btnAyuda.addEventListener('click', async () => {
    let i = 0;
    const steps = [
      {
        icon: 'question',
        title: 'Historial de Compras',
        html: `<div class="text-start">
                 <p>Consulta y filtra las <b>compras registradas</b>. Puedes iniciar una <b>Nueva Compra</b> o revisar el detalle de cada una.</p>
               </div>`,
        onOpen: () => highlight($('#btn-nueva-compra'))
      },
      {
        icon: 'info',
        title: 'Rango de fechas',
        html: `<div class="text-start">
                 <p>Usa <b>Desde</b> y <b>Hasta</b> para limitar el período mostrado.</p>
                 <small class="text-muted">Formato: Dia; Mes; Año.</small>
               </div>`,
        onOpen: () => highlight($('#filtro_desde')) || highlight($('#filtro_hasta'))
      },
      {
        icon: 'info',
        title: 'Filtrar / Limpiar',
        html: `<div class="text-start">
                 <ul class="mb-0">
                   <li><b>Filtrar</b> aplica el rango seleccionado.</li>
                   <li><b>Limpiar</b> borra filtros y muestra todo.</li>
                 </ul>
               </div>`,
        onOpen: () => highlight($('#btn-filtrar')) || highlight($('#btn-limpiar'))
      },
      {
        icon: 'info',
        title: 'Tabla de compras',
        html: `<div class="text-start">
                 <p>Columnas:</p>
                 <ul class="mb-0">
                   <li><b>Fecha</b> de registro.</li>
                   <li><b>Productos</b>: cantidad de productos en compra.</li>
                   <li><b>Orígenes</b> De donde proceden.</li>
                   <li><b>Unidades</b> totales.</li>
                   <li><b>Total</b> de la compra.</li>
                   <li><b>Usuario</b> que registró.</li>
                   <li><b>Acciones</b>: <em>Ver</em> detalle.</li>
                 </ul>
               </div>`,
        onOpen: () => highlight($('#tabla-historial-compras'))
      },
      {
        icon: 'info',
        title: 'Ver detalle',
        html: `<div class="text-start">
                 <p>Usa <b>Ver</b> para abrir el detalle de ítems y montos de la compra seleccionada.</p>
               </div>`,
        onOpen: () => highlight(firstInTable('.btn-ver-compra')) || highlight(firstInTable('a.btn-outline-primary'))
      }
    ];

    const modal = Swal.mixin({
      showCancelButton: false,
      focusConfirm: true,
      confirmButtonText: 'Siguiente',
      confirmButtonColor: '#3085d6',
      width: 600,
      allowOutsideClick: false,
      didOpen: () => {
        const s = steps[i];
        if (s && typeof s.onOpen === 'function') setTimeout(s.onOpen, 50);
      }
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

