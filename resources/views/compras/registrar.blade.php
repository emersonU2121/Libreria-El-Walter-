@extends('menu')

@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Registrar Nueva Compra</h2>
        <a href="{{ route('compras.mostrar') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Historial de compras
        </a>
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
                
                <!-- Productos -->
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
                                    <!-- Producto -->
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
    
    <small class="text-muted d-block mt-1">
        Nuevo Stock: 
        <span class="fw-semibold text-primary nuevo-stock" data-stock-actual="0">0</span>
    </small>
    
</div>
                                    
                                    <!-- Origen/Concepto -->
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-dark">Origen</label>
                                        <input type="text" name="productos[0][concepto]" class="form-control form-control-sm" 
                                               placeholder="Ej: El mercado..." required>
                                    </div>

                                    <!-- Unidades -->
                                    <div class="col-md-1">
                                        <label class="form-label small fw-semibold text-dark">Unidades</label>
                                        <input type="number" name="productos[0][unidades]" class="form-control form-control-sm unidades" 
                                               min="1" placeholder="Cant" required>
                                    </div>

                                    <!-- PRECIO COMPRA EDITABLE -->
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


                                    <!-- Precio Unitario (Calculado) -->
                                    <div class="col-md-1">
                                        <label class="form-label small fw-semibold text-dark">P. Unitario</label>
                                        <div class="bg-light rounded p-2 border text-center">
                                            <strong class="text-success">$<span class="precio-unitario">0.00</span></strong>
                                        </div>
                                    </div>

                                    <!-- Precio Total -->
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-dark">Precio Total</label>
                                        <div class="bg-light rounded p-2 border text-center">
                                            <strong class="text-primary">$<span class="precio-total">0.00</span></strong>
                                        </div>
                                    </div>

                                    <!-- Botón Eliminar -->
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

                <!-- Total y Botón -->
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
<!-- Modal Confirmar Cancelación -->
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

<!-- Modal Aviso Validación -->
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



<script>
let productoIndex = 0;
let filaActualParaProducto = null; // Variable GLOBAL para saber qué fila actualizar

// --- NUEVA LÓGICA DEL MODAL ---

// 1. Guardar la fila que abrió el modal
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-buscar-producto')) {
        filaActualParaProducto = e.target.closest('.producto-item');
        // Resetea el filtro del modal cada vez que se abre
        const filtro = document.getElementById('filtro-producto-modal');
        if (filtro) {
            filtro.value = '';
            filtro.dispatchEvent(new Event('input')); // Simula un 'input' para resetear la lista
        }
    }
});

// 2. Seleccionar un producto del modal y enviarlo a la fila
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-seleccionar-producto');
    if (btn) {
        if (!filaActualParaProducto) return;

        // Obtener datos del botón de la tarjeta
        const id = btn.dataset.id;
        const nombre = btn.dataset.nombre;
        const precio = parseFloat(btn.dataset.precio) || 0;
        const stock = parseInt(btn.dataset.stock) || 0;

        // Encontrar los campos en la fila guardada
        const inputId = filaActualParaProducto.querySelector('.producto-id-hidden');
        const nombreDisplay = filaActualParaProducto.querySelector('.producto-nombre-display');
        const precioInput = filaActualParaProducto.querySelector('.precio-compra-editable');
        const stockDisplay = filaActualParaProducto.querySelector('.nuevo-stock');

        // Poner los datos en la fila
        inputId.value = id;
        nombreDisplay.textContent = nombre;
        nombreDisplay.classList.remove('text-muted');
        nombreDisplay.classList.remove('small');

        // Guardar el stock actual en el data-attribute
        stockDisplay.dataset.stockActual = stock;
        
        // Autocompletar precio de compra (si está vacío)
        if (precioInput.value === '0.00' || precioInput.value === '') {
            precioInput.value = precio.toFixed(2);
        }
        
        // Disparar cálculos
        calcularProducto(filaActualParaProducto);
        calcularTotal();

        // Limpiar la variable global
        filaActualParaProducto = null;
    }
});

// 3. Filtro de búsqueda en el modal
document.getElementById('filtro-producto-modal')?.addEventListener('input', function(e) {
    const filtro = e.target.value.toLowerCase();
    document.querySelectorAll('#lista-productos-modal .producto-card-modal').forEach(card => {
        const nombre = card.querySelector('.card-title').textContent.toLowerCase();
        if (nombre.includes(filtro)) {
            card.style.display = 'block'; // Muestra la columna
        } else {
            card.style.display = 'none'; // Oculta la columna
        }
    });
});


// --- LÓGICA EXISTENTE (ACTUALIZADA) ---

// Agregar nuevo producto
document.getElementById('btn-agregar-producto').addEventListener('click', function() {
    const container = document.getElementById('productos-container');
    const newProducto = container.firstElementChild.cloneNode(true);
    
    productoIndex++;
    
    // Actualizar índices y limpiar valores
    newProducto.querySelectorAll('[name]').forEach(input => {
        const name = input.getAttribute('name').replace('[0]', `[${productoIndex}]`);
        input.setAttribute('name', name);
        if (input.type !== 'hidden') input.value = '';
    });
    
    // Limpiar campos calculados
    newProducto.querySelector('.precio-unitario').textContent = '0.00';
    newProducto.querySelector('.precio-total').textContent = '0.00';
    
    // Limpiar display de producto
    const nombreDisplay = newProducto.querySelector('.producto-nombre-display');
    nombreDisplay.textContent = 'No seleccionado...';
    nombreDisplay.classList.add('text-muted');
    nombreDisplay.classList.add('small');
    
    // Limpiar input oculto de ID
    newProducto.querySelector('.producto-id-hidden').value = '';

    // Limpiar precio de compra
    newProducto.querySelector('.precio-compra-editable').value = '0.00';

    // Limpiar display de stock
    const stockDisplay = newProducto.querySelector('.nuevo-stock');
    stockDisplay.textContent = '0';
    stockDisplay.dataset.stockActual = '0';
    
    container.appendChild(newProducto);
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
    if (e.target.classList.contains('unidades') || 
        e.target.classList.contains('precio-compra-editable')) {
        const productoItem = e.target.closest('.producto-item');
        calcularProducto(productoItem);
        calcularTotal();
    }
});

// (La función de 'change' en el select ya no es necesaria)

function calcularProducto(productoItem) {
    const unidades = parseFloat(productoItem.querySelector('.unidades').value) || 0;
    const precio = parseFloat(productoItem.querySelector('.precio-compra-editable').value) || 0;
    
    // Calcular precios
    const precioTotal = precio * unidades;
    
    // Mostrar resultados
    productoItem.querySelector('.precio-unitario').textContent = precio.toFixed(2);
    productoItem.querySelector('.precio-total').textContent = precioTotal.toFixed(2);
    
    // Calcular nuevo stock (AHORA LEE DEL DATA-ATTRIBUTE)
    const stockDisplay = productoItem.querySelector('.nuevo-stock');
    const stockActual = parseInt(stockDisplay.dataset.stockActual) || 0;
    stockDisplay.textContent = stockActual + unidades;
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

// --- BOTÓN: Cancelar Operación (versión con modal bonito) ---
document.addEventListener('DOMContentLoaded', function () {
  const btnCancelar = document.getElementById('btnCancelarCompra');
  const btnConfirmar = document.getElementById('btnConfirmarCancelacion');
  const modalCancel = new bootstrap.Modal(document.getElementById('modalConfirmarCancelacion'));
  const form = document.getElementById('form-compra');

  if (!btnCancelar || !btnConfirmar || !form) return;

  // Mostrar el modal al hacer clic en "Cancelar"
  btnCancelar.addEventListener('click', function () {
    modalCancel.show();
  });

  // Confirmar limpieza del formulario
  btnConfirmar.addEventListener('click', function () {
    modalCancel.hide(); // Cerrar el modal

    // --- Limpieza total del formulario ---
    form.reset();
    productoIndex = 0;

    const container = document.getElementById('productos-container');
    const firstRow = container.firstElementChild;
    [...container.querySelectorAll('.producto-item')].slice(1).forEach(el => el.remove());

    if (firstRow) {
      firstRow.querySelectorAll('input').forEach(input => input.value = '');
      const nombreDisplay = firstRow.querySelector('.producto-nombre-display');
      if (nombreDisplay) {
        nombreDisplay.textContent = 'No seleccionado...';
        nombreDisplay.classList.add('text-muted', 'small');
      }
      const stockDisplay = firstRow.querySelector('.nuevo-stock');
      if (stockDisplay) {
        stockDisplay.textContent = '0';
        stockDisplay.dataset.stockActual = '0';
      }
      firstRow.querySelector('.precio-unitario').textContent = '0.00';
      firstRow.querySelector('.precio-total').textContent = '0.00';
    }

    const totalLbl = document.getElementById('total-compra');
    if (totalLbl) totalLbl.textContent = '0.00';
  });
});
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('form-compra');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    const filas = Array.from(document.querySelectorAll('.producto-item'));
    
    // Verifica si todas las filas tienen producto seleccionado
    const hayIncompletas = filas.some(f => {
      const idProd = (f.querySelector('.producto-id-hidden')?.value || '').trim();
      return idProd === ''; // fila vacía
    });

    if (hayIncompletas) {
      e.preventDefault();
      const modal = new bootstrap.Modal(document.getElementById('modalAvisoValidacion'));
      document.getElementById('avisoMsg').textContent =
        'No puedes registrar la compra. Todas las filas deben tener un producto seleccionado.';
      modal.show();
    }
  });
});
</script>




<style>
.producto-item {
    transition: all 0.2s ease;
}

.producto-item:hover {
    border-color: #0d6efd !important;
}

.card {
    border-radius: 8px;
}

.btn {
    border-radius: 6px;
}

.form-control {
    border-radius: 6px;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.precio-compra-editable:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}



/* Estilos para las tarjetas del modal */
#lista-productos-modal .card {
    transition: all 0.2s ease;
    border: 1px solid #dee2e6;
}

#lista-productos-modal .card:hover {
    border-color: #0d6efd;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#lista-productos-modal .card-footer {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

/* Estilo para el display del producto en la fila */
.producto-display {
    min-height: 31px; /* Misma altura que un form-control-sm */
    display: flex;
    align-items: center;
    background-color: #f8f9fa;
}
</style>
@endsection