<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="#" method="post">
        @csrf
        {{-- @method('PUT') --}}
        <div class="modal-header">
          <h5 class="modal-title">Editar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" id="edit_user_id">
          <div class="mb-3">
            <label for="edit_name" class="form-label">Nombre</label>
            <input type="text" id="edit_name" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_email" class="form-label">Correo</label>
            <input type="email" id="edit_email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_role" class="form-label">Rol</label>
            <select id="edit_role" name="role" class="form-select">
              <option value="">Sin rol</option>
              <option value="Administrador">Administrador</option>
              <option value="Empleado">Empleado</option>
            </select>
          </div>
          <div class="form-check">
            <input type="checkbox" id="edit_activo" name="activo" class="form-check-input">
            <label for="edit_activo" class="form-check-label">Activo</label>
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
