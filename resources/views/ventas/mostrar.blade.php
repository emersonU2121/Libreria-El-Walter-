@extends('menu')

@section('contenido')

{{-- Estilos básicos --}}
<style>
.paginacion-sencilla {
  display: flex;
  gap: 0.5rem;
  align-items: center;
}
.paginacion-btn {
  padding: 6px 12px;
  text-decoration: none;
  color: #007bff;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 0.9rem;
  transition: all 0.2s ease;
}
.paginacion-btn:hover {
  background: #007bff;
  color: white;
}
.paginacion-btn.deshabilitado {
  color: #aaa;
  border-color: #eee;
  cursor: not-allowed;
  background: #f9f9f9;
}
</style>
<div class="container-fluid py-4 mt-5 px-3">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 text-dark">Historial de Ventas</h2>
    <a href="{{ route('ventas.registrar') }}" class="btn btn-primary">
      <i class="fas fa-plus me-2"></i>Nueva Venta
    </a>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0 text-dark">Ventas Registradas</h5>

        {{-- Filtro de fechas --}}
        <form action="{{ route('ventas.mostrar') }}" method="GET" class="d-flex align-items-end gap-2">
          <div>
            <label class="form-label mb-1 small text-muted">Desde</label>
            <input type="date" name="desde" value="{{ request('desde') }}" class="form-control form-control-sm">
          </div>
          <div>
            <label class="form-label mb-1 small text-muted">Hasta</label>
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control form-control-sm">
          </div>
          <button class="btn btn-sm btn-primary"><i class="fas fa-filter me-1"></i>Filtrar</button>
          <a href="{{ route('ventas.mostrar') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
        </form>
      </div>
    </div>

    <div class="card-body">
      @if($ventas->isEmpty())
        <div class="alert alert-info mb-0 text-center">No hay ventas registradas.</div>
      @else
        <div class="table-responsive">
          <table class="table table-hover table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th># Venta</th>
                <th>Número de Factura</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Total</th>
                <th>Items</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($ventas as $v)
                <tr>
                  <td>{{ $v->idventa }}</td>
                  <td>{{ $v->numero_factura }}</td>
                  <td>{{ \Carbon\Carbon::parse($v->fecha)->format('d/m/Y') }}</td>
                  <td>{{ $v->usuario->nombre ?? 'N/A' }}</td>
                  <td class="text-success fw-semibold">${{ number_format($v->total,2) }}</td>
                  <td>{{ $v->detalles->sum('unidades') }}</td>
                  <td>
                    <a href="{{ route('ventas.detalles', $v->idventa) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Paginación + contador --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
  <div class="small text-muted">
    Mostrando {{ $ventas->firstItem() }}–{{ $ventas->lastItem() }} de {{ $ventas->total() }} registros
  </div>

  <div class="paginacion-sencilla">
    {{-- Botón anterior --}}
    @if ($ventas->onFirstPage())
      <span class="paginacion-btn deshabilitado">‹ Anterior</span>
    @else
      <a href="{{ $ventas->previousPageUrl() }}" class="paginacion-btn">‹ Anterior</a>
    @endif

    {{-- Botón siguiente --}}
    @if ($ventas->hasMorePages())
      <a href="{{ $ventas->nextPageUrl() }}" class="paginacion-btn">Siguiente ›</a>
    @else
      <span class="paginacion-btn deshabilitado">Siguiente ›</span>
    @endif
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const desde = document.querySelector('input[name="desde"]');
  const hasta = document.querySelector('input[name="hasta"]');
  if (desde && hasta) {
    if (desde.value) hasta.min = desde.value;
    if (hasta.value) desde.max = hasta.value;
    desde.addEventListener('change', () => hasta.min = desde.value || '');
    hasta.addEventListener('change', () => desde.max = hasta.value || '');
  }
});
</script>
@endpush
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const $ = (s) => document.querySelector(s);

  // Efecto de resaltado
  function highlight(el) {
    if (!el) return false;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    el.classList.add('help-pulse');
    setTimeout(() => el.classList.remove('help-pulse'), 1400);
    return true;
  }

  // Botón de ayuda en Historial de Ventas
  const btnAyuda = $('#btn-ayuda'); // usa este id en tu botón "?"
  if (!btnAyuda) return;

  btnAyuda.addEventListener('click', async () => {
    let i = 0;

    // Pasos del tour para HISTORIAL DE VENTAS
    const steps = [
      {
        icon: 'question',
        title: 'Nueva Venta',
        html: `<div class="text-start">
                 <p>Pulsa <b>Nueva Venta</b> para abrir el formulario y registrar una venta.</p>
               </div>`,
        onOpen: () =>
          highlight($('#btn_nueva_venta')) ||
          highlight(document.querySelector('a[href*="ventas/registrar"]'))
      },
      {
        icon: 'info',
        title: 'Filtrar por fechas',
        html: `<div class="text-start">
                 <p>Usa <b>Desde</b> y <b>Hasta</b> (<code>dd/mm/aaaa</code>) para definir el rango.</p>
               </div>`,
        onOpen: () =>
          highlight($('#ventas_desde')) ||
          highlight(document.querySelector('input[name="desde"]')) ||
          highlight(document.querySelector('input[type="date"]'))
      },
      {
        icon: 'info',
        title: 'Aplicar / Limpiar filtros',
        html: `<div class="text-start">
                 <ul class="mb-0">
                   <li><b>Filtrar</b>: aplica el rango seleccionado.</li>
                   <li><b>Limpiar</b>: quita el filtro y muestra todas las ventas.</li>
                 </ul>
               </div>`,
        onOpen: () =>
          highlight($('#ventas_btn_filtrar')) ||
          highlight(document.querySelector('button[type="submit"],a.btn-primary')) ||
          highlight($('#ventas_btn_limpiar')) ||
          highlight(document.querySelector('a[href*="limpiar"],button.btn-secondary'))
      },
      {
        icon: 'info',
        title: 'Tabla de Ventas',
        html: `<div class="text-start">
                 <p>Aquí verás <b># Venta</b>, <b>Número de factura</b>, <b>Fecha</b>, <b>Usuario</b>, <b>Total</b> e <b>Ítems</b>.</p>
               </div>`,
        onOpen: () =>
          highlight($('#tabla_ventas')) ||
          highlight(document.querySelector('table'))
      },
      {
        icon: 'info',
        title: 'Ver detalle de venta',
        html: `<div class="text-start">
                 <p>En <b>Acciones</b>, usa <b>Ver</b> para abrir el detalle con los productos vendidos.</p>
               </div>`,
        onOpen: () =>
          highlight(document.querySelector('.btn-ver-venta')) ||
          highlight(document.querySelector('a.btn.btn-outline-primary, a.btn.btn-sm'))
      },
      {
        icon: 'info',
        title: 'Paginación',
        html: `<div class="text-start">
                 <p>Navega con <b>Previous</b> y <b>Next</b> (las flechas) para recorrer los resultados.</p>
               </div>`,
        onOpen: () =>
          highlight(document.querySelector('a[rel="prev"], button[aria-label="Previous"]')) ||
          highlight($('#pager_prev')) ||
          highlight(document.querySelector('a[rel="next"], button[aria-label="Next"]')) ||
          highlight($('#pager_next'))
      }
    ];

    // Config común del modal
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

    // Loop de pasos
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
/* Resalte visual del elemento enfocado */
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

