document.addEventListener('DOMContentLoaded', () => {

    // --- VARIABLES GLOBALES ---
    let carrito = []; // Array que almacena los productos del carrito
    const productListEl = document.getElementById('product-list');
    const searchBarEl = document.getElementById('search-bar');
    const cartItemsListEl = document.getElementById('cart-items-list');
    const cartEmptyMsgEl = document.getElementById('cart-empty-msg');
    const cartTotalEl = document.getElementById('cart-total');
    const cartCountEl = document.getElementById('cart-count');
    const btnRegistrarVenta = document.getElementById('btn-registrar-venta');
    const btnCancelarVenta = document.getElementById('btn-cancelar-venta');
    const modalErrorStock = new bootstrap.Modal(document.getElementById('modalErrorStock'));
    const modalErrorMsg = document.getElementById('modalErrorStockMessage');

    // --- INICIALIZACIÓN ---
    renderizarProductos(allProducts); // Dibuja todos los productos al cargar

    // --- EVENT LISTENERS ---
    
    // 1. Barra de Búsqueda
    searchBarEl.addEventListener('input', (e) => {
        const termino = e.target.value.toLowerCase();
        const productosFiltrados = allProducts.filter(producto => 
            producto.nombre.toLowerCase().includes(termino)
        );
        renderizarProductos(productosFiltrados);
    });

    // 2. Clic en un Producto del Catálogo
    productListEl.addEventListener('click', (e) => {
        const card = e.target.closest('.product-card');
        if (card && !card.classList.contains('out-of-stock')) {
            const id = parseInt(card.dataset.id);
            agregarAlCarrito(id);
        }
    });

    // 3. Clics dentro del Carrito (Aumentar, Disminuir, Quitar)
    cartItemsListEl.addEventListener('click', (e) => {
        const target = e.target;
        const itemEl = target.closest('.cart-item');
        if (!itemEl) return;
        
        const id = parseInt(itemEl.dataset.id);

        if (target.classList.contains('btn-qty-increase')) {
            actualizarCantidad(id, 1); // Aumenta en 1
        }
        if (target.classList.contains('btn-qty-decrease')) {
            actualizarCantidad(id, -1); // Disminuye en 1
        }
        if (target.classList.contains('btn-remove-item')) {
            quitarDelCarrito(id);
        }
    });
    
    // 4. Input manual de cantidad en carrito
    cartItemsListEl.addEventListener('change', (e) => {
        if(e.target.classList.contains('quantity-input')) {
            const itemEl = e.target.closest('.cart-item');
            const id = parseInt(itemEl.dataset.id);
            let nuevaCantidad = parseInt(e.target.value);
            
            if (isNaN(nuevaCantidad) || nuevaCantidad < 1) {
                nuevaCantidad = 1; // Mínimo 1
            }
            
            // Re-valida la cantidad contra el stock
            actualizarCantidad(id, nuevaCantidad, true); // true = es un set, no un incremento
        }
    });

    // 5. Botón Registrar Venta
    btnRegistrarVenta.addEventListener('click', () => {
        registrarVenta();
    });
    
    // 6. Botón Cancelar Venta
    btnCancelarVenta.addEventListener('click', async () => {
    Swal.fire({
        title: '¿Cancelar venta?',
        text: 'Se eliminarán todos los productos del carrito y se reiniciará la página.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'No, continuar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            carrito = [];
            renderizarCarrito();
            renderizarProductos(allProducts);
            
            // Pequeña alerta de confirmación
            Swal.fire({
                icon: 'success',
                title: 'Venta cancelada',
                text: 'La página se reiniciará.',
                showConfirmButton: false,
                timer: 1200
            });

            // Recarga la página después del mensaje
            setTimeout(() => window.location.reload(), 1300);
        }
    });
});


    // --- FUNCIONES PRINCIPALES ---

    /**
     * Dibuja las tarjetas de productos en el catálogo
     */
    function renderizarProductos(productos) {
        productListEl.innerHTML = ''; // Limpia la lista
        if (productos.length === 0) {
            productListEl.innerHTML = '<div class="col-12"><p class="text-muted text-center">No se encontraron productos.</p></div>';
            return;
        }

        productos.forEach(producto => {
            // Revisa si el producto ya está en el carrito para saber el stock real
            const itemEnCarrito = carrito.find(item => item.id === producto.idproducto);
            const stockDisponible = itemEnCarrito ? (producto.stock - itemEnCarrito.cantidad) : producto.stock;
            const isOutOfStock = stockDisponible <= 0;

            const cardHTML = `
                <div class="col">
                    <div class="card product-card ${isOutOfStock ? 'out-of-stock' : ''}" 
                         data-id="${producto.idproducto}" 
                         data-nombre="${producto.nombre}"
                         data-precio_venta="${producto.precio_venta}"
                         data-stock="${producto.stock}">
                        
                        <img src="${producto.imagen ? '/storage/' + producto.imagen : '/images/no-image.png'}" 
                             class="card-img-top" alt="${producto.nombre}">
                        
                        <div class="card-body">
                            <h6 class="card-title product-name">${producto.nombre}</h6>
                            <p class="card-text product-price">$${parseFloat(producto.precio_venta).toFixed(2)}</p>
                            <p class="card-text product-stock">Stock: ${stockDisponible}</p>
                        </div>
                    </div>
                </div>
            `;
            productListEl.insertAdjacentHTML('beforeend', cardHTML);
        });
    }

    /**
     * Dibuja los items en el carrito y actualiza los totales
     */
    function renderizarCarrito() {
        cartItemsListEl.innerHTML = ''; // Limpia la lista
        let total = 0;
        let totalItems = 0;

        if (carrito.length === 0) {
            cartEmptyMsgEl.style.display = 'block';
        } else {
            cartEmptyMsgEl.style.display = 'none';
            carrito.forEach(item => {
                const subtotalItem = item.precio_venta * item.cantidad;
                total += subtotalItem;
                totalItems += item.cantidad;

                const itemHTML = `
                    <li class="list-group-item cart-item d-flex justify-content-between align-items-center" data-id="${item.id}">
                        <div class="flex-grow-1 me-2">
                            <div class="cart-item-name">${item.nombre}</div>
                            <div class="cart-item-price">${item.cantidad} x $${item.precio_venta.toFixed(2)}</div>
                        </div>
                        
                        <div class="quantity-controls me-2">
                            <button class="btn btn-outline-secondary btn-sm btn-qty-decrease">-</button>
                            <input type="number" class="quantity-input" value="${item.cantidad}" min="1" max="${item.stock}">
                            <button class="btn btn-outline-secondary btn-sm btn-qty-increase">+</button>
                        </div>
                        
                        <div class="fw-bold me-2" style="width: 70px; text-align: right;">
                            $${subtotalItem.toFixed(2)}
                        </div>
                        
                        <button class="btn btn-outline-danger btn-sm btn-remove-item">
                            <i class="fas fa-times"></i>
                        </button>
                    </li>
                `;
                cartItemsListEl.insertAdjacentHTML('beforeend', itemHTML);
            });
        }

        cartTotalEl.textContent = `$${total.toFixed(2)}`;
        cartCountEl.textContent = `${totalItems} items`;
    }

    /**
     * Agrega un producto al carrito o incrementa su cantidad
     */
    function agregarAlCarrito(id) {
        const producto = allProducts.find(p => p.idproducto === id);
        const itemEnCarrito = carrito.find(item => item.id === id);

        if (itemEnCarrito) {
            // Producto ya está en el carrito, solo incrementa cantidad
            if (itemEnCarrito.cantidad < producto.stock) {
                itemEnCarrito.cantidad++;
            } else {
                mostrarErrorStock('No hay más stock disponible para este producto.');
            }
        } else {
            // Producto no está en el carrito, agrégalo
            if (producto.stock > 0) {
                carrito.push({
                    id: producto.idproducto,
                    nombre: producto.nombre,
                    precio_venta: parseFloat(producto.precio_venta),
                    cantidad: 1,
                    stock: producto.stock
                });
            } else {
                mostrarErrorStock('Este producto está agotado.');
            }
        }
        
        renderizarCarrito();
        renderizarProductos(allProducts.filter(p => searchBarEl.value === '' || p.nombre.toLowerCase().includes(searchBarEl.value.toLowerCase())));
    }

    /**
     * Quita un producto del carrito
     */
    function quitarDelCarrito(id) {
        carrito = carrito.filter(item => item.id !== id);
        renderizarCarrito();
        renderizarProductos(allProducts.filter(p => searchBarEl.value === '' || p.nombre.toLowerCase().includes(searchBarEl.value.toLowerCase())));
    }

    /**
     * Cambia la cantidad de un item en el carrito
     * @param {boolean} esSet - Si es true, 'cambio' es la nueva cantidad. Si es false, 'cambio' es un incremento (+1 o -1).
     */
    function actualizarCantidad(id, cambio, esSet = false) {
        const itemEnCarrito = carrito.find(item => item.id === id);
        if (!itemEnCarrito) return;

        let nuevaCantidad;
        if (esSet) {
            nuevaCantidad = cambio;
        } else {
            nuevaCantidad = itemEnCarrito.cantidad + cambio;
        }

        // Validaciones
        if (nuevaCantidad < 1) {
            quitarDelCarrito(id);
            return;
        }
        
        if (nuevaCantidad > itemEnCarrito.stock) {
            nuevaCantidad = itemEnCarrito.stock;
            mostrarErrorStock('No hay más stock disponible. Se ajustó al máximo.');
        }

        itemEnCarrito.cantidad = nuevaCantidad;
        renderizarCarrito();
        renderizarProductos(allProducts.filter(p => searchBarEl.value === '' || p.nombre.toLowerCase().includes(searchBarEl.value.toLowerCase())));
    }
    
    /**
     * Muestra el modal de error
     */
     function mostrarErrorStock(mensaje) {
        modalErrorMsg.textContent = mensaje;
        modalErrorStock.show();
     }

    /**
     * Envía el carrito al servidor para registrar la venta
     */
    async function registrarVenta() {
        if (carrito.length === 0) {
            mostrarErrorStock('El carrito está vacío. Agrega al menos un producto.');
            return;
        }
        
        // Deshabilitar botón para evitar doble clic
        btnRegistrarVenta.disabled = true;
        btnRegistrarVenta.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...';

        const total = parseFloat(cartTotalEl.textContent.replace('$', ''));

        try {
            const response = await fetch(storeSaleUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    total: total,
                    productos: carrito 
                })
            });

            const data = await response.json();

           if (data.ok) {
    new Audio('https://cdn.pixabay.com/audio/2022/03/15/audio_9a8d8c3c12.mp3').play();
    
    Swal.fire({
      icon: 'success',
      title: '¡Venta registrada!',
      html: `
        <p>La factura se generó correctamente.</p>
        <p><b>No. de Factura:</b> ${data.numero_factura || '—'}</p>
        <p><b>Total:</b> $${parseFloat(data.total || 0).toFixed(2)}</p>
      `,
      showCancelButton: true,
      confirmButtonText: 'Ver factura',
      cancelButtonText: 'Cerrar',
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
    }).then((result) => {
      if (result.isConfirmed && data.pdf) {
        window.open(data.pdf, '_blank');
      }
      // ✅ Refrescar la página después de cerrar el SweetAlert
  setTimeout(() => {
    window.location.reload();
  }, 500);
    });

    
    carrito = [];
    renderizarCarrito();
    renderizarProductos(allProducts);
    
    btnRegistrarVenta.disabled = false;
    btnRegistrarVenta.innerHTML = '<i class="fas fa-check-circle me-2"></i>Registrar Venta';
} else {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: data.message || 'No se pudo registrar la venta',
    });
}
        } catch (error) {
            // Captura errores de red o errores lanzados desde el servidor
            mostrarErrorStock(error.message);
            // Habilitar botón de nuevo
            btnRegistrarVenta.disabled = false;
            btnRegistrarVenta.innerHTML = '<i class="fas fa-check-circle me-2"></i>Registrar Venta';
        }
    }

});