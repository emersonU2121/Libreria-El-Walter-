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

@endsection



@push('scripts')
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