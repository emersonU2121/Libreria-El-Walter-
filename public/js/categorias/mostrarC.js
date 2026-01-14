// ==== OBTENER DATOS GLOBALES DE LAS META TAGS ====
const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
const validateUrlEl = document.querySelector('meta[name="categorias-validate-url"]'); // Lee la nueva meta tag

if (!csrfTokenEl || !validateUrlEl) {
    console.error('Faltan las meta tags "csrf-token" o "categorias-validate-url" en el HTML.');
}

const csrfToken = csrfTokenEl ? csrfTokenEl.getAttribute('content') : '';
const categoriasValidateUrl = validateUrlEl ? validateUrlEl.getAttribute('content') : ''; // Guarda la URL


// ==== EDITAR =====
document.querySelectorAll('.btn-open-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('edit_idcategoria').value = btn.dataset.idcategoria;
        document.getElementById('edit_nombre').value = btn.dataset.nombre || '';
        const f = document.getElementById('formEditarCategoria');
        if (f) f.action = btn.dataset.updateUrl || '#';
    });
});

// ==== ELIMINAR (click directo) ====
document.querySelectorAll('.btn-open-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.idcategoria;
        const nombre = btn.dataset.nombre || '';
        const f = document.getElementById('formEliminarCategoria');
        const msg = document.getElementById('eliminar_message');
        const hid = document.getElementById('eliminar_idcategoria');

        if (hid) hid.value = id;
        if (msg) msg.innerHTML = `¿Estás seguro de eliminar la categoría <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
        if (f) f.action = btn.dataset.deleteUrl || '#';
    });
});

// ==== ELIMINAR (fallback: show.bs.modal) ====
const modalEliminarEl = document.getElementById('modalEliminar');
if (modalEliminarEl) {
    modalEliminarEl.addEventListener('show.bs.modal', (ev) => {
        const btn = ev.relatedTarget; if (!btn) return;
        const id = btn.getAttribute('data-idcategoria');
        const nombre = btn.getAttribute('data-nombre') || '';
        const f = document.getElementById('formEliminarCategoria');
        const msg = document.getElementById('eliminar_message');
        const hid = document.getElementById('eliminar_idcategoria');

        if (hid) hid.value = id;
        if (msg) msg.innerHTML = `¿Estás seguro de eliminar la categoría <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
        if (f) f.action = btn.getAttribute('data-delete-url') || '#';
    });
}

// Salvaguarda: evitar submit sin action
['formEditarCategoria','formEliminarCategoria'].forEach(id => {
    const f = document.getElementById(id);
    if (f) f.addEventListener('submit', e => {
        if (!f.action || f.action.endsWith('#')) {
            e.preventDefault();
            alert('No se pudo determinar el destino del formulario.');
        }
    });
});


// Validación AJAX del nombre al editar
const formEditar = document.getElementById('formEditarCategoria');
if (formEditar) {
    formEditar.addEventListener('submit', function(e) {
        e.preventDefault();
        formEditar.querySelectorAll('.text-danger').forEach(el => el.remove());

        const nombreActual = document.getElementById('edit_nombre').value;
        const idcategoria  = document.getElementById('edit_idcategoria').value;

        const botonOriginal = document.querySelector('.btn-open-edit[data-idcategoria="' + idcategoria + '"]');
        if (!botonOriginal) { formEditar.submit(); return; }

        const nombreOriginal = botonOriginal.dataset.nombre;
        if (nombreActual === nombreOriginal) { formEditar.submit(); return; }

        fetch(categoriasValidateUrl, { // 👈 USA LA VARIABLE
            method: 'POST',
            headers: { 
                "Content-Type": "application/json", 
                "X-CSRF-TOKEN": csrfToken // 👈 USA LA VARIABLE
            },
            body: JSON.stringify({ nombre: nombreActual, idcategoria })
        })
        .then(res => res.json())
        .then(data => {
            if (data.duplicado) {
                const err = document.createElement('div');
                err.className = 'text-danger small mt-1';
                err.textContent = 'La categoría ya ha sido registrada.';
                document.getElementById('edit_nombre').after(err);
            } else {
                formEditar.submit();
            }
        })
        .catch(() => formEditar.submit());
    });
}


// Limpiar errores del modal al cerrar
const modalEditar = document.getElementById('modalEditar');
if (modalEditar) {
    modalEditar.addEventListener('hide.bs.modal', function () {
        document.querySelectorAll('#modalEditar .text-danger').forEach(el => el.style.display = 'none');
    });
}

// Validación del formulario de registro
const formRegistro = document.getElementById('formRegistro');
if (formRegistro) {
    const nombreInput = document.getElementById('nombre');
    const errorJsRegistro = document.getElementById('error-js-registro-cat'); // Div de error para JS

    nombreInput.addEventListener('input', function() {
        if (nombreInput.value.trim() === '') {
             nombreInput.classList.add('is-invalid');
             if(errorJsRegistro) errorJsRegistro.classList.remove('d-none');
        } else {
             nombreInput.classList.remove('is-invalid');
             if(errorJsRegistro) errorJsRegistro.classList.add('d-none');
        }
    });

    formRegistro.addEventListener('submit', function(e) {
        if (nombreInput.value.trim() === '') {
            e.preventDefault();
            nombreInput.classList.add('is-invalid');
            if(errorJsRegistro) errorJsRegistro.classList.add('d-block');
            nombreInput.focus();
        }
    });
}