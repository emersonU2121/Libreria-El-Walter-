@php
    if (!isset($marcas)) {
        $marcas = \Illuminate\Support\Facades\DB::table('marca')
                    ->orderBy('nombre')->get(['idmarca','nombre']);
    }
    if (!isset($categorias)) {
        $categorias = \Illuminate\Support\Facades\DB::table('categoria')
                      ->orderBy('nombre')->get(['idcategoria','nombre']);
    }
@endphp

<div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post" id="formEditarProducto">
        @csrf @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Editar producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="idproducto" id="edit_idproducto">

          <div class="mb-3">
            <label for="edit_nombre" class="form-label">Nombre</label>
            <input type="text" id="edit_nombre" name="nombre" class="form-control" required>
          </div>

         <div class="mb-3">
  <label for="edit_precio" class="form-label">Precio unitario</label>
  <input type="text" inputmode="decimal" id="edit_precio" name="precio" class="form-control" placeholder="0,00 o 0.00" required>
  <small class="text-muted">Solo números y hasta 2 decimales.</small>
</div>

<div class="mb-3">
  <label for="edit_precio_venta" class="form-label">Precio de venta</label>
  <input type="text" inputmode="decimal" id="edit_precio_venta" name="precio_venta" class="form-control" placeholder="0,00 o 0.00" required>
  <small class="text-muted">Solo números y hasta 2 decimales.</small>
  <div id="err_pv_edit" class="text-danger small d-none">El precio de venta no puede ser menor que el precio unitario.</div>

</div>


          <div class="mb-3">
            <label for="edit_stock" class="form-label">Existencias</label>
            <input type="number" min="0" id="edit_stock" name="stock" class="form-control" required>
          </div>


          <div class="mb-3">
            <label for="edit_idmarca" class="form-label">Marca</label>
            <select id="edit_idmarca" name="idmarca" class="form-select" required>
              @foreach($marcas as $m)
                <option value="{{ $m->idmarca }}">{{ $m->nombre }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label for="edit_idcategoria" class="form-label">Categoría</label>
            <select id="edit_idcategoria" name="idcategoria" class="form-select" required>
              @foreach($categorias as $c)
                <option value="{{ $c->idcategoria }}">{{ $c->nombre }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>

  <script>
(() => {
  const p  = document.getElementById('edit_precio');
  const pv = document.getElementById('edit_precio_venta');

  function formatMoney(raw) {
    let s = (raw || '').toString().replace(/[^0-9.,]/g, '');
    const i = s.search(/[.,]/);
    if (i === -1) {
      return s.replace(/[.,]/g,'').replace(/^0+(?=\d)/,'');
    }
    const sep = s[i];
    const ent = s.slice(0, i).replace(/[.,]/g,'').replace(/^0+(?=\d)/,'');
    const dec = s.slice(i + 1).replace(/[.,]/g,'').slice(0, 2);
    const entFinal = ent.length ? ent : '0';
    return dec.length ? (entFinal + sep + dec) : (entFinal + sep);
  }

  [p, pv].forEach(inp => {
    inp?.addEventListener('input', () => {
      inp.value = formatMoney(inp.value);
    });
  });

  const formEdit = document.getElementById('formEditarProducto');
  formEdit?.addEventListener('submit', () => {
    [p, pv].forEach(inp => {
      if (!inp) return;
      let v = (inp.value || '').trim();
      if (/[.,]$/.test(v)) v = v.slice(0, -1);
      inp.value = v.replace(',', '.'); // coma -> punto
    });
  });
})();
</script>
<script>
// === Validar que el precio de venta no sea menor que el precio (Editar) ===
document.addEventListener('DOMContentLoaded', () => {
  const formEdit = document.getElementById('formEditarProducto');
  if (!formEdit) return;

  const p = document.getElementById('edit_precio');
  const pv = document.getElementById('edit_precio_venta');

  // Crear mensaje de error dinámico si no existe
  let err = document.getElementById('err_pv_edit');
  if (!err) {
    err = document.createElement('div');
    err.id = 'err_pv_edit';
    err.className = 'text-danger small d-none';
    err.textContent = 'El precio de venta no puede ser menor que el precio.';
    pv.parentElement.appendChild(err);
  }

  formEdit.addEventListener('submit', (e) => {
    let vP = (p.value || '').trim();
    let vPV = (pv.value || '').trim();

    // Normaliza coma -> punto
    if (/[.,]$/.test(vP)) vP = vP.slice(0, -1);
    if (/[.,]$/.test(vPV)) vPV = vPV.slice(0, -1);
    vP = vP.replace(',', '.');
    vPV = vPV.replace(',', '.');

    const nP = parseFloat(vP) || 0;
    const nPV = parseFloat(vPV) || 0;

    if (nPV < nP) {
      e.preventDefault();
      pv.classList.add('is-invalid');
      err.classList.remove('d-none');
      return;
    } else {
      pv.classList.remove('is-invalid');
      err.classList.add('d-none');
    }

    // reasigna valores normalizados
    p.value = vP;
    pv.value = vPV;
  });
});
</script>


</div>
