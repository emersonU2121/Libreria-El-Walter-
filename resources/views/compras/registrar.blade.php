@extends('menu')

@section('contenido')
<div class="container-fluid py-4">
    <!-- Header de la página -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Registrar Nueva Compra</h2>
        <a href="{{ route('compras.mostrar') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Historial de compras
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0">Productos Comprados</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('compras.store') }}" method="POST" id="form-compra">
                @csrf
                
                <!-- Productos -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Items de Compra</h5>
                        <button type="button" class="btn btn-primary" id="btn-agregar-producto">
                            <i class="fas fa-plus"></i> Agregar Producto
                        </button>
                    </div>
                    
                    <div id="productos-container">
                        <div class="producto-item card mb-3">
                            <div class="card-body">
                                <!-- Concepto por producto -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Lugar de compra para este producto:</label>
                                        <input type="text" name="productos[0][concepto]" class="form-control concepto-producto" 
                                               placeholder="Ej: El mercado, Super Selectos, Distribuidora XYZ..." required>
                                    </div>
                                </div>
                                
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Producto</label>
                                        <select name="productos[0][id_producto]" class="form-control producto-select" required>
                                            <option value="">Seleccionar producto...</option>
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->idproducto }}" data-precio="{{ $producto->precio }}">
                                                    {{ $producto->nombre }} (Stock: {{ $producto->stock }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold">Unidades</label>
                                        <input type="number" name="productos[0][unidades]" class="form-control unidades" 
                                               min="1" placeholder="Cantidad" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold">Precio Unitario</label>
                                        <div class="form-control-plaintext bg-light rounded p-2">
                                            <strong class="text-success">$<span class="precio-unitario">0.00</span></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold">Precio Total</label>
                                        <div class="form-control-plaintext bg-light rounded p-2">
                                            <strong class="text-primary">$<span class="precio-total">0.00</span></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold">Nuevo Stock</label>
                                        <div class="form-control-plaintext bg-light rounded p-2">
                                            <strong class="text-info"><span class="nuevo-stock">0</span></strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger btn-remove btn-sm">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total y Botón -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="bg-light rounded p-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-0 text-dark">Total de la Compra: 
                                        <span class="text-success">$<span id="total-compra">0.00</span></span>
                                    </h4>
                                </div>
                                <div class="col-md-6 text-end">
                                    <button type="submit" class="btn btn-success btn-lg px-4">
                                        <i class="fas fa-save"></i> Registrar Compra
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let productoIndex = 1;

// Agregar nuevo producto
document.getElementById('btn-agregar-producto').addEventListener('click', function() {
    const container = document.getElementById('productos-container');
    const newProducto = container.firstElementChild.cloneNode(true);
    
    // Actualizar índices y limpiar valores
    newProducto.querySelectorAll('[name]').forEach(input => {
        const name = input.getAttribute('name').replace('[0]', `[${productoIndex}]`);
        input.setAttribute('name', name);
        if (input.type !== 'hidden') input.value = '';
    });
    
    // Limpiar campos calculados
    newProducto.querySelector('.precio-unitario').textContent = '0.00';
    newProducto.querySelector('.precio-total').textContent = '0.00';
    newProducto.querySelector('.nuevo-stock').textContent = '0';
    
    container.appendChild(newProducto);
    productoIndex++;
});

// Remover producto
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove') || e.target.closest('.btn-remove')) {
        const btn = e.target.classList.contains('btn-remove') ? e.target : e.target.closest('.btn-remove');
        if (document.querySelectorAll('.producto-item').length > 1) {
            btn.closest('.producto-item').remove();
            calcularTotal();
        }
    }
});

// Cálculos en tiempo real
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('unidades') || e.target.classList.contains('producto-select')) {
        const productoItem = e.target.closest('.producto-item');
        calcularProducto(productoItem);
        calcularTotal();
    }
});

// Cuando se selecciona un producto
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('producto-select')) {
        const productoItem = e.target.closest('.producto-item');
        calcularProducto(productoItem);
        calcularTotal();
    }
});

function calcularProducto(productoItem) {
    const select = productoItem.querySelector('.producto-select');
    const unidades = parseFloat(productoItem.querySelector('.unidades').value) || 0;
    const precio = parseFloat(select.selectedOptions[0]?.dataset.precio) || 0;
    
    // Calcular precios
    const precioTotal = precio * unidades;
    
    // Mostrar resultados
    productoItem.querySelector('.precio-unitario').textContent = precio.toFixed(2);
    productoItem.querySelector('.precio-total').textContent = precioTotal.toFixed(2);
    
    // Calcular nuevo stock (stock actual + unidades compradas)
    const stockActual = parseInt(select.selectedOptions[0]?.textContent.match(/Stock: (\d+)/)?.[1]) || 0;
    productoItem.querySelector('.nuevo-stock').textContent = stockActual + unidades;
}

function calcularTotal() {
    let total = 0;
    document.querySelectorAll('.producto-item').forEach(item => {
        const precioTotal = parseFloat(item.querySelector('.precio-total').textContent) || 0;
        total += precioTotal;
    });
    document.getElementById('total-compra').textContent = total.toFixed(2);
}

// Inicializar cálculos
document.addEventListener('DOMContentLoaded', function() {
    calcularTotal();
});
</script>

<style>
.producto-item {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.producto-item:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.card-header {
    background: #f8f9fa !important;
}

.form-control {
    border-radius: 6px;
}

.btn-success {
    border-radius: 8px;
    font-weight: 600;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection