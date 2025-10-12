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
            <label for="edit_precio" class="form-label">Precio</label>
            <input type="number" step="0.01" min="0" id="edit_precio" name="precio" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_stock" class="form-label">Stock</label>
            <input type="number" min="0" id="edit_stock" name="stock" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_estado" class="form-label">Estado</label>
            <select id="edit_estado" name="estado" class="form-select">
              <option value="disponible">disponible</option>
              <option value="agotado">agotado</option>
            </select>
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
</div>