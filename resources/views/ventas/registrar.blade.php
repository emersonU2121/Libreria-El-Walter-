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
                    <button type="button" class="btn btn-outline-info" id="btn-camara-pos" title="Buscar por código">
                            <i class="fas fa-barcode me-1"></i> Buscar por código
                        </button>
                          <button type="button" class="btn btn-outline-secondary" id="btn-limpiar-pos" title="Limpiar búsqueda">
        <i class="fas fa-eraser me-1"></i> Limpiar
    </button>
                      </div>
                      <div id="contenedor-camara-pos" class="mt-2" style="display:none;">
                        <video id="video-pos"
                            style="width:50%; height:200px; object-fit:cover; border:1px solid #ddd; border-radius:6px; background:#000;"
                            autoplay muted playsinline></video>
                        <div class="small text-muted mt-1">Enfoca el código de barras...</div>
                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" id="btn-detener-pos">
                            Detener cámara
                        </button>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnCamara  = document.getElementById('btn-camara-pos');
    const cont       = document.getElementById('contenedor-camara-pos');
    const video      = document.getElementById('video-pos');
    const btnDetener = document.getElementById('btn-detener-pos');
    const inputSearch = document.getElementById('search-bar');
    const productList = document.getElementById('product-list');
    const btnLimpiar = document.getElementById('btn-limpiar-pos');


if (btnLimpiar) {
    btnLimpiar.addEventListener('click', function() {
        // limpiar input
        if (inputSearch) {
            inputSearch.value = '';
            inputSearch.dispatchEvent(new Event('input')); // refresca el filtro
        }

        // detener cámara si está activa
        if (cont && cont.style.display !== 'none') {
            detener();
        }

        // opcional: mostrar todos los productos si tu JS del POS lo soporta
        if (typeof window.posMostrarTodos === 'function') {
            window.posMostrarTodos();
        }
    });
}
    let stream = null;
    let yaDetecto = false;

    function detener() {
        yaDetecto = false;
        if (typeof Quagga !== 'undefined') {
            Quagga.stop();
        }
        if (stream) {
            stream.getTracks().forEach(t => t.stop());
            stream = null;
        }
        if (cont) cont.style.display = 'none';
        if (video) video.srcObject = null;
    }

    // intenta agregar al carrito según el ID (porque en tu caso el idproducto ES el código)
    function intentarAgregarPorId(code) {
        // 1) si tu JS del POS expone una función global, la usamos
        if (typeof window.posAgregarProductoPorId === 'function') {
            window.posAgregarProductoPorId(code);
            return true;
        }

        // 2) si tus tarjetas tienen data-idproducto, intentamos clic
        if (productList) {
            const cardBtn = productList.querySelector('[data-idproducto="'+code+'"], [data-id="'+code+'"]');
            if (cardBtn) {
                cardBtn.click();
                return true;
            }
        }

        // 3) si no lo encontró, al menos dejamos el código en el buscador
        if (inputSearch) {
            inputSearch.value = code;
            inputSearch.dispatchEvent(new Event('input'));
        }
        return false;
    }

    function iniciarQuagga() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: video,
                constraints: {
                    facingMode: "user",
                    width: 1280,
                    height: 720
                }
            },
            decoder: {
                readers: [
                    "code_128_reader",
                    "ean_reader",
                    "ean_8_reader",
                    "code_39_reader",
                    "upc_reader",
                    "upc_e_reader"
                ]
            },
            locator: {
                patchSize: "medium",
                halfSample: true
            },
            locate: true,
            numOfWorkers: navigator.hardwareConcurrency || 2
        }, function(err) {
            if (err) {
                console.error(err);
                detener();
                return;
            }
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            if (yaDetecto) return;
            if (result && result.codeResult && result.codeResult.code) {
                let code = result.codeResult.code.trim();

                // mismo fix: si tu lector agrega 0 al inicio, se lo quitamos
                if (code.startsWith('0')) {
                    code = code.substring(1);
                }

                intentarAgregarPorId(code);

                // apagamos la cámara
                setTimeout(detener, 300);
                yaDetecto = true;
            }
        });
    }

    if (btnCamara) {
        btnCamara.addEventListener('click', async function() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                cont.style.display = 'block';
                iniciarQuagga();
            } catch (e) {
                alert('No se pudo acceder a la cámara');
            }
        });
    }

    if (btnDetener) {
        btnDetener.addEventListener('click', detener);
    }

    window.addEventListener('beforeunload', detener);
});



</script>
@endpush