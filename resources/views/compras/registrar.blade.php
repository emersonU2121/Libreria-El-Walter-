@extends('menu')
@push('styles')
    <link href="{{ asset('css/compras/registrar.css') }}" rel="stylesheet">
@endpush
@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0 text-dark">Registrar Nueva Compra</h2>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalListaDeCompra">
            <i class="fas fa-print me-2"></i>Crear Lista de Compra (PDF)
        </button>
        <a href="{{ route('compras.mostrar') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Historial de compras
        </a>
    </div>
</div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Productos Comprados</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('compras.store') }}" method="POST" id="form-compra">
                @csrf
                <div class="mb-3">
    <label for="concepto_general" class="form-label fw-semibold text-dark">
    Concepto General de la Compra
    </label>
        <input type="text" 
               name="concepto_general" 
               id="concepto_general" 
               class="form-control" 
               placeholder="Ej: Pedido semanal a proveedor X, Compra de libros para inventario..." 
               required
               pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
               title="Solo se permiten letras y espacios"
               oninput="this.value = this.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '')">

</div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-dark fw-semibold">Items de Compra</h6>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-agregar-producto">
                            <i class="fas fa-plus me-1"></i>Agregar Producto
                        </button>
                    </div>
                    
                    <div id="productos-container">
                        <div class="producto-item card border mb-3">
                            <div class="card-body">
                                <div class="row g-3 align-items-start">
                                    <div class="col-md-3">
    <label class="form-label small fw-semibold text-dark">Producto</label>
    
    <input type="hidden" name="productos[0][id_producto]" class="form-control form-control-sm producto-id-hidden" required>
    
    <div class="card card-body p-2 producto-display mb-2">
        <span class="producto-nombre-display text-muted small">No seleccionado...</span>
    </div>

    <button type="button" class="btn btn-outline-primary btn-sm w-100 btn-buscar-producto" 
            data-bs-toggle="modal" data-bs-target="#modalBuscarProducto">
        <i class="fas fa-search me-1"></i> Buscar Producto
    </button>
    
</div>
                                    
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-dark">Origen</label>
                                        <input type="text" name="productos[0][concepto]" class="form-control form-control-sm" 
                                               placeholder="Ej: El mercado..." required>
                                    </div>

                                    <div class="col-md-1">
                                        <label class="form-label small fw-semibold text-dark">Unidades</label>
                                        <input type="number" name="productos[0][unidades]" class="form-control form-control-sm unidades" 
                                               min="1" placeholder="Cant" required>
                                    </div>

                                    <div class="col-md-2">
                                    <label class="form-label small fw-semibold text-dark">Precio Compra</label>
                                    <input type="number"
                                           name="productos[0][precio_compra]"
                                           class="form-control form-control-sm precio-compra-editable"
                                           step="0.01"
                                           min="0.01"
                                           value="0.00"
                                           placeholder="0.00"
                                           required>
                                    </div>


                                    <div class="col-md-1">
                                        <label class="form-label small fw-semibold text-dark">P. Unitario</label>
                                        <div class="bg-light rounded p-2 border text-center">
                                            <strong class="text-success">$<span class="precio-unitario">0.00</span></strong>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-dark">Precio Total</label>
                                        <div class="bg-light rounded p-2 border text-center">
                                            <strong class="text-primary">$<span class="precio-total">0.00</span></strong>
                                        </div>
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-remove">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="bg-light rounded p-3 border">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="mb-0 text-dark">Total de la Compra: 
                                        <span class="text-success">$<span id="total-compra">0.00</span></span>
                                    </h5>
                                </div>
                                <div class="col-md-6 text-end">
                                <div class="d-inline-flex gap-2">
                                <button type="button" id="btnCancelarCompra" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-ban me-2"></i>Cancelar Operación
                                </button>

                                <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save me-2"></i>Registrar Compra
                                </button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBuscarProducto" tabindex="-1" aria-labelledby="modalBuscarProductoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBuscarProductoLabel">Seleccionar Producto</h5>
                <input type="text" class="form-control ms-3" id="filtro-producto-modal" placeholder="Buscar por nombre...">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="lista-productos-modal" class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
                    
                    @foreach($productos as $producto)
                    <div class="col producto-card-modal">
                        <div class="card h-100">
                            <img src="{{ $producto->imagen ? asset('storage/'.$producto->imagen) : asset('images/no-image.png') }}" 
                                 class="card-img-top" 
                                 alt="{{ $producto->nombre }}" 
                                 style="height: 180px; object-fit: cover;">
                            
                            <div class="card-body">
                                <h6 class="card-title fw-semibold text-dark">{{ $producto->nombre }}</h6>
                                <p class="card-text small mb-1">
                                    <strong>Precio Compra:</strong> ${{ number_format($producto->precio, 2) }}
                                </p>
                                <p class="card-text small">
                                    <strong>Stock Actual:</strong> {{ $producto->stock }}
                                </p>
                            </div>
                            <div class="card-footer">
                                <button type="button" 
                                        class="btn btn-sm btn-primary w-100 btn-seleccionar-producto"
                                        data-id="{{ $producto->idproducto }}"
                                        data-nombre="{{ $producto->nombre }}"
                                        data-precio="{{ $producto->precio }}"
                                        data-stock="{{ $producto->stock }}"
                                        data-bs-dismiss="modal">
                                    Seleccionar
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalConfirmarCancelacion" tabindex="-1" aria-labelledby="modalConfirmarCancelacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalConfirmarCancelacionLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar cancelación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="fw-semibold mb-3 text-dark">
                    ¿Deseas cancelar la operación actual? Todos los campos y productos agregados se eliminarán.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, volver</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmarCancelacion">Sí, cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalAvisoValidacion" tabindex="-1" aria-labelledby="modalAvisoValidacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-sm">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalAvisoValidacionLabel">
                    <i class="fas fa-exclamation-circle me-2"></i>Validación de compra
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark" id="avisoMsg">
                Debes agregar al menos un producto para registrar la compra.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalListaDeCompra" tabindex="-1" aria-labelledby="modalListaDeCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('productos.listaDeCompraPdf') }}" method="POST" target="_blank">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalListaDeCompraLabel">Crear Lista de Compra</h5>
                    <input type="text" class="form-control ms-3" id="filtro-lista-compra-modal" placeholder="Buscar por nombre...">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="lista-compra-modal-cards" class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
                        
                        @forelse($productosBajoStock as $producto)
                        <div class="col producto-card-lista">
                            <div class="card h-100 card-lista-compra">
                                <img src="{{ $producto->imagen ? asset('storage/'.$producto->imagen) : asset('images/no-image.png') }}" 
                                     class="card-img-top" alt="{{ $producto->nombre }}" style="height: 180px; object-fit: cover;">
                                
                                <div class="card-body">
                                    <h6 class="card-title fw-semibold text-dark">{{ $producto->nombre }}</h6>
                                    <p class="card-text small text-danger">
                                        <strong>Stock Actual: {{ $producto->stock }}</strong>
                                    </p>
                                </div>
                                <div class="card-footer text-center">
                                    <div class="form-check">
                                        <input class="form-check-input check-lista-producto" 
                                               type="checkbox" 
                                               name="producto_ids[]"
                                               value="{{ $producto->idproducto }}" 
                                               id="check-lista-{{ $producto->idproducto }}">
                                        <label class="form-check-label fw-semibold" for="check-lista-{{ $producto->idproducto }}">
                                            Seleccionar
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-success text-center">
                                No hay productos con bajo stock (stock > 0).
                            </div>
                        </div>
                        @endforelse

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btn-generar-lista-pdf">
                        <i class="fas fa-print me-2"></i>Generar lista de compra
                    </button>
                </div>
            </form>
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
    <script src="{{ asset('js/compras/registrar.js') }}"></script>
@endpush