<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post" id="formEditarCategoria">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Editar Categoría</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="idcategoria" id="edit_idcategoria">

          <div class="mb-3">
            <label for="edit_nombre" class="form-label">Nombre de la Categoría</label>
            <input
              type="text"
              id="edit_nombre"
              name="nombre"
              class="form-control"
              required
              value="{{ old('nombre') }}"
            >
            @error('nombre')
              <div class="text-danger small">{{ $message }}</div>
            @enderror
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
