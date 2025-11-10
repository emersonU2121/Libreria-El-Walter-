@extends('menu')


@push('styles')
    <link href="{{ asset('css/ventas/pos.css') }}" rel="stylesheet">
@endpush


@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Punto de Venta</h2>
        <a href="{{ route('ventas.mostrar') }}" class="btn btn-outline-secondary">
            <i class="fas fa-history me-2"></i>Historial de Ventas
        </a>
    </div>

    <!-- Contenedor principal del POS -->
    <div class="row g-4" id="pos-container">

        <!-- Columna Izquierda: Catálogo de Productos (7) -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" id="product-catalog">
                <!-- Barra de Búsqueda -->
                <div class="card-header bg-white p-3 border-bottom">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="search-bar" class="form-control" placeholder="Buscar producto por nombre...">
                    </div>
                </div>
                <!-- Lista de Productos -->
                <div class="card-body" id="product-list-container">
                    <div id="product-list" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3">
                        <!-- Las tarjetas de productos se insertarán aquí por JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Carrito de Venta (5) -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm" id="cart-container">
                <div class="card-header bg-white p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark"><i class="fas fa-shopping-cart me-2"></i>Carrito de Venta</h5>
                    <span class="badge bg-primary rounded-pill" id="cart-count">0 items</span>
                </div>
                <!-- Items del Carrito -->
                <div class="card-body" id="cart-items-container">
                    <ul class="list-group list-group-flush" id="cart-items-list">
                        <!-- Placeholder si está vacío -->
                        <li id="cart-empty-msg" class="list-group-item text-center text-muted">
                            El carrito está vacío
                        </li>
                    </ul>
                </div>
                <!-- Total y Botones -->
                <div class="card-footer bg-white p-3" id="cart-footer">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0 text-dark">Total:</h4>
                        <h4 class="mb-0 text-success" id="cart-total">$0.00</h4>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-lg" id="btn-registrar-venta">
                            <i class="fas fa-check-circle me-2"></i>Registrar Venta
                        </button>
                        <button class="btn btn-outline-danger" id="btn-cancelar-venta">
                            <i class="fas fa-ban me-2"></i>Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- .row -->
</div> <!-- .container-fluid -->

<!-- Modales -->
@include('ventas._modal_error_stock')

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
    <!-- Pasamos datos de Blade a JS -->
    <script>
        // Pasamos la lista de productos de Blade a una variable JS
        const allProducts = @json($productos);
        // Pasamos las URLs de las rutas
        const storeSaleUrl = "{{ route('ventas.store') }}";
        const csrfToken = "{{ csrf_token() }}";
    </script>
    
    <!-- Incluimos el archivo JS principal del POS -->
    <script src="{{ asset('js/ventas/pos.js') }}"></script>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const $ = (s) => document.querySelector(s);

  // Efecto de resalte
  function highlight(el) {
    if (!el) return false;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    el.classList.add('help-pulse');
    setTimeout(() => el.classList.remove('help-pulse'), 1400);
    return true;
  }

  const btnAyuda = $('#btn-ayuda');    // botón flotante “?”
  if (!btnAyuda) return;

  btnAyuda.addEventListener('click', async () => {
    let i = 0;

    // Pasos del tour para el POS
    const steps = [
      {
        icon: 'question',
        title: 'Bienvenido al Punto de Venta',
        html: `<div class="text-start">
                 <p>Aquí puedes <b>buscar productos</b>, <b>agregarlos al carrito</b> y <b>registrar la venta</b>.</p>
               </div>`,
        onOpen: () =>
          highlight($('#pos-container')) ||
          highlight($('.container-fluid'))
      },
      {
        icon: 'info',
        title: 'Buscar producto',
        html: `<div class="text-start">
                 <p>Escribe en la barra de búsqueda para filtrar productos por nombre.</p>
               </div>`,
        onOpen: () =>
          highlight($('#search-bar')) ||
          highlight(document.querySelector('input[placeholder*="Buscar producto"]'))
      },
      {
        icon: 'info',
        title: 'Catálogo de productos',
        html: `<div class="text-start">
                 <p>Haz <b>clic</b> sobre una tarjeta para añadir el producto al carrito.</p>
               </div>`,
        onOpen: () =>
          highlight($('#product-catalog')) ||
          highlight(document.querySelector('#product-list .product-card')) ||
          highlight($('#product-list'))
      },
      {
        icon: 'info',
        title: 'Carrito de Venta',
        html: `<div class="text-start">
                 <p>Verás los productos añadidos, con sus cantidades y subtotales.</p>
               </div>`,
        onOpen: () =>
          highlight($('#cart-container')) ||
          highlight($('#cart-items-container')) ||
          highlight($('#cart-items-list'))
      },
      {
        icon: 'info',
        title: 'Modificar cantidades / quitar',
        html: `<div class="text-start">
                 <ul class="mb-0">
                   <li>Usa <b>+</b> y <b>-</b> para ajustar cantidades.</li>
                   <li>También puedes escribir la cantidad en el campo numérico.</li>
                   <li>El botón rojo quita el producto del carrito.</li>
                 </ul>
               </div>`,
        onOpen: () =>
          highlight(document.querySelector('.quantity-input')) ||
          highlight(document.querySelector('.btn-qty-increase')) ||
          highlight(document.querySelector('.btn-qty-decrease'))
      },
      {
        icon: 'info',
        title: 'Total y contador',
        html: `<div class="text-start">
                 <p>El <b>Total</b> y los <b>items</b> se actualizan automáticamente.</p>
               </div>`,
        onOpen: () =>
          highlight($('#cart-total')) ||
          highlight($('#cart-count'))
      },
      {
        icon: 'success',
        title: 'Registrar Venta',
        html: `<div class="text-start">
                 <p>Guarda la venta. Si todo está correcto, verás una <b>alerta</b> y podrás <b>abrir la factura PDF</b>.</p>
               </div>`,
        onOpen: () =>
          highlight($('#btn-registrar-venta'))
      },
      {
        icon: 'warning',
        title: 'Cancelar',
        html: `<div class="text-start">
                 <p>Vacía el carrito y reinicia la operación.</p>
               </div>`,
        onOpen: () =>
          highlight($('#btn-cancelar-venta'))
      },
      {
        icon: 'info',
        title: 'Historial de Ventas',
        html: `<div class="text-start">
                 <p>Desde aquí puedes volver al listado con todas las ventas.</p>
               </div>`,
        onOpen: () =>
          highlight($('#btn-historial-ventas')) ||
          highlight(document.querySelector('a[href*="ventas/mostrar"]'))
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
