// ==== OBTENER DATOS GLOBALES DE LAS META TAGS ====
// Lee el token CSRF y la URL de validación del HTML
const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
const validateUrlEl = document.querySelector('meta[name="marcas-validate-url"]');

// Si no existen las meta tags, no podemos continuar con AJAX
if (!csrfTokenEl || !validateUrlEl) {
    console.error('Faltan las meta tags "csrf-token" o "marcas-validate-url" en el HTML.');
}

const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';
const marcasValidateUrl = validateUrlEl ? validateUrlEl.getAttribute('content') : '';


// ==== LÓGICA PARA MODAL DE EDITAR =====
const modalEditarEl = document.getElementById('modalEditar');
const formEditar = document.getElementById('formEditarMarca');
const editNombreInput = document.getElementById('edit_nombre');
const editErrorContainer = document.getElementById('error-container-edit'); // El div de error de AJAX

// 1. Rellenar el modal de Editar al hacer clic en el botón
document.querySelectorAll('.btn-open-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        // Setea los valores del modal
        document.getElementById('edit_idmarca').value = btn.dataset.idmarca;
        editNombreInput.value = btn.dataset.nombre || '';
        if (formEditar) formEditar.action = btn.dataset.updateUrl || '#';
        
        // Limpiar errores viejos al abrir
        editNombreInput.classList.remove('is-invalid');
        if (editErrorContainer) editErrorContainer.innerHTML = '';
    });
});

// 2. Lógica de validación AJAX para el modal de Editar
if (formEditar && csrfToken && marcasValidateUrl) { // Solo si todo existe
    formEditar.addEventListener('submit', function(e) {
        e.preventDefault(); // Detener el envío normal

        const nombreActual = editNombreInput.value;
        const idmarca = document.getElementById('edit_idmarca').value;
        
        // Buscar el botón original para obtener el nombre original
        const botonOriginal = document.querySelector('.btn-open-edit[data-idmarca="' + idmarca + '"]');
        const nombreOriginal = botonOriginal ? botonOriginal.dataset.nombre : '';

        // Si el nombre no cambió, envía el formulario sin validar
        if (nombreActual === nombreOriginal) {
            formEditar.submit();
            return;
        }
        
        // Limpiar errores antes de validar
        editNombreInput.classList.remove('is-invalid');
        if (editErrorContainer) editErrorContainer.innerHTML = '';

        // Si el nombre cambió, validar con AJAX
        fetch(marcasValidateUrl, { // 👈 CORRECCIÓN
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken // 👈 CORRECCIÓN
            },
            body: JSON.stringify({ 
                nombre: nombreActual, 
                idmarca: idmarca // Enviar el ID para excluirlo en la validación
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.duplicado) {
                // Mostrar error
                editNombreInput.classList.add('is-invalid');
                if(editErrorContainer) {
                     editErrorContainer.innerHTML = '<div class="invalid-feedback d-block">La marca ya ha sido registrada.</div>';
                } else {
                    alert('La marca ya ha sido registrada.');
                }
            } else {
                // No duplicado, enviar formulario
                formEditar.submit();
            }
        })
        .catch(error => {
            console.error('Error en la validación AJAX:', error);
            formEditar.submit(); // Enviar de todos modos si falla el AJAX
        });
    });
}

// 3. Limpiar errores del modal de Editar al cerrar
if (modalEditarEl) {
    modalEditarEl.addEventListener('hide.bs.modal', function () {
        editNombreInput.classList.remove('is-invalid');
        if (editErrorContainer) editErrorContainer.innerHTML = '';
    });
}


// ==== LÓGICA PARA MODAL DE ELIMINAR ====
document.querySelectorAll('.btn-open-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.idmarca;
        const nombre = btn.dataset.nombre || '';
        const f = document.getElementById('formEliminarMarca');
        const msg = document.getElementById('eliminar_message');
        const hid = document.getElementById('eliminar_idmarca');

        if (hid) hid.value = id;
        if (msg) {
            msg.innerHTML = `¿Estás seguro de eliminar la marca <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
        }
        if (f) f.action = btn.dataset.deleteUrl || '#';
    });
});


// ==== LÓGICA PARA VALIDACIÓN DEL FORMULARIO DE REGISTRO ====
const formRegistro = document.getElementById('formRegistro');
if (formRegistro) {
    const nombreInput = document.getElementById('nombre');
    const errorJsRegistro = document.getElementById('error-js-registro'); // El div de error

    // Validación en tiempo real
    nombreInput.addEventListener('input', function() {
        if (nombreInput.value.trim() === '') {
            nombreInput.classList.add('is-invalid');
            if (errorJsRegistro) errorJsRegistro.classList.remove('d-none'); // Muestra el error de JS
        } else {
            nombreInput.classList.remove('is-invalid');
            if (errorJsRegistro) errorJsRegistro.classList.add('d-none'); // Oculta el error de JS
        }
    });

    // Validación al enviar
    formRegistro.addEventListener('submit', function(e) {
        if (nombreInput.value.trim() === '') {
            e.preventDefault();
            nombreInput.classList.add('is-invalid');
            if (errorJsRegistro) errorJsRegistro.classList.remove('d-none'); // Muestra el error de JS
            nombreInput.focus();
        }
    });
}

// Salvaguarda: evita POST incorrecto si faltara 'action' en modales
['formEditarMarca','formEliminarMarca'].forEach(id => {
    const f = document.getElementById(id);
    if (f) {
        f.addEventListener('submit', e => {
            if (!f.action || f.action.endsWith('#')) {
                e.preventDefault();
                alert('No se pudo determinar el destino del formulario.');
            }
        });
    }
});

// (El script para re-abrir el modal si hay error se queda en el Blade)