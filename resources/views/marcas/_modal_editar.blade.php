<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post" id="formEditarMarca">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Editar Marca</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="idMarca" id="edit_idmarca">

          <div class="mb-3">
            <label for="edit_nombre" class="form-label">Nombre de la Marca</label>
            <input type="text" id="edit_nombre" name="nombre" class="form-control" required value="{{ old('nombre') }}">
            @if ($errors->has('nombre'))
              <div class="text-danger small">
                {{ $errors->first('nombre') }}
              </div>
            @endif
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