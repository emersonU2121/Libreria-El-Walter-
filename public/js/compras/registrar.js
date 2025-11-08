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

        // Poner los datos en la fila
        inputId.value = id;
        nombreDisplay.textContent = nombre;
        nombreDisplay.classList.remove('text-muted');
        nombreDisplay.classList.remove('small');
        
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
            firstRow.querySelector('.precio-unitario').textContent = '0.00';
            firstRow.querySelector('.precio-total').textContent = '0.00';
        }

        const totalLbl = document.getElementById('total-compra');
        if (totalLbl) totalLbl.textContent = '0.00';
    });
});





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

// --- LÓGICA PARA EL NUEVO MODAL DE LISTA DE COMPRA ---

// 1. Filtro de búsqueda en el modal de Lista de Compra
document.getElementById('filtro-lista-compra-modal')?.addEventListener('input', function(e) {
    const filtro = e.target.value.toLowerCase();
    document.querySelectorAll('#lista-compra-modal-cards .producto-card-lista').forEach(card => {
        const nombre = card.querySelector('.card-title').textContent.toLowerCase();
        card.style.display = nombre.includes(filtro) ? 'block' : 'none';
    });
});

// 2. Hacer clickeable la card para marcar/desmarcar el checkbox
document.addEventListener('click', function(e) {
    const card = e.target.closest('.card-lista-compra');
    if (card) {
        // Evita que el click en el checkbox se dispare dos veces
        if (e.target.classList.contains('check-lista-producto')) return;
        
        const checkbox = card.querySelector('.check-lista-producto');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            // Dispara el evento 'change' para que el CSS se actualice
            checkbox.dispatchEvent(new Event('change'));
        }
    }
});

// 3. Cambiar estilo visual de la card cuando el checkbox cambia
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('check-lista-producto')) {
        const card = e.target.closest('.card-lista-compra');
        if (card) {
            card.classList.toggle('selected', e.target.checked);
        }
    }
});
