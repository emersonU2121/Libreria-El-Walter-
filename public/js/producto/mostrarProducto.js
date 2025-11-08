document.addEventListener('DOMContentLoaded', () => {
    // Pega tu script JS aquí
    const BASE_STORAGE = "{{ asset('storage') }}";
    const NO_IMAGE     = "{{ asset('images/no-image.png') }}";

    document.querySelectorAll('.btn-open-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        const f = document.getElementById('formEditarProducto');
        if (f) f.action = btn.dataset.updateUrl || '#';

        ['idproducto','nombre','precio','precio_venta','stock','idmarca','idcategoria'].forEach(k => {
            const el = document.getElementById('edit_'+k);
            if (el) el.value = btn.dataset[k] ?? '';
        });

        const prev = document.getElementById('edit_preview_img');
        if (prev) {
            const rel = btn.dataset.imagen || '';
            prev.src = rel ? (BASE_STORAGE + '/' + rel) : NO_IMAGE;
        }

        const s = document.getElementById('edit_stock');
        const estView = document.getElementById('edit_estado_view');
        const hint = document.getElementById('edit_low_hint');
        const n = parseInt(s?.value || '0', 10);
        if (estView) estView.value = (n > 0) ? 'disponible' : 'agotado';
        if (hint) { if (n > 0 && n <= 5) hint.classList.remove('d-none'); else hint.classList.add('d-none'); }
    });
    });

    document.querySelectorAll('.btn-open-baja').forEach(btn => {
    btn.addEventListener('click', () => {
        const esActivo = btn.dataset.activo === '1';
        const f    = document.getElementById('formBajaProducto');
        const msg  = document.getElementById('baja_message_prod');
        const hid  = document.getElementById('baja_idproducto');
        const sBtn = document.getElementById('baja_submit_btn_prod');

        if (hid) hid.value = btn.dataset.idproducto;
        if (msg) {
        msg.innerHTML = esActivo
            ? `¿Estás seguro de dar de baja el producto <strong>${btn.dataset.nombre}</strong>?`
            : `¿Deseas reactivar el producto <strong>${btn.dataset.nombre}</strong>?`;
        }
        if (f) f.action = esActivo ? (btn.dataset.inactivarUrl || '#') : (btn.dataset.activarUrl || '#');

        if (sBtn) {
        sBtn.textContent = esActivo ? 'Sí, dar de baja' : 'Reactivar';
        sBtn.classList.remove('btn-success','btn-warning');
        sBtn.classList.add(esActivo ? 'btn-warning' : 'btn-success');
        }
    });
    });

    ['formEditarProducto','formBajaProducto'].forEach(id => {
    const f = document.getElementById(id);
    if (!f) return;
    f.addEventListener('submit', e => {
        if (!f.action || f.action.endsWith('#')) {
        e.preventDefault();
        alert('No se pudo determinar el destino del formulario.');
        }
    });
    });
});
