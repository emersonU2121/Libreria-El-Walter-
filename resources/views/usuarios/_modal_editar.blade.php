<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post" id="formEditarUsuario"> {{-- action lo pone el JS --}}
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Editar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="idusuario" id="edit_idusuario">

          <div class="mb-3">
            <label for="edit_nombre" class="form-label">Nombre</label>
            <input type="text" id="edit_nombre" name="nombre" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_correo" class="form-label">Correo</label>
            <input type="email" id="edit_correo" name="correo" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_rol" class="form-label">Rol</label>
            <select id="edit_rol" name="rol" class="form-select">
              <option value="">Sin rol</option>
              <option value="Administrador">Administrador</option>
              <option value="Empleado">Empleado</option>
            </select>
          </div>

          <div class="mb-2">
            <label for="edit_contrasena" class="form-label">Contraseña (opcional)</label>
            <input type="password" id="edit_contrasena" name="contraseña" class="form-control" placeholder="Deja vacío para no cambiar">
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
