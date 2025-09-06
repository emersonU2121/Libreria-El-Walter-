<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      {{-- TODO: Reemplaza # por la ruta real, ej. route('usuarios.update', id) y descomenta @method('PUT') --}}
      <form action="#" method="post">
        @csrf
        {{-- @method('PUT') --}}

        <div class="modal-header">
          <h5 class="modal-title">Editar usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          {{-- Debe coincidir con el dataset de la tabla: data-idusuario="..." --}}
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

          {{-- Opcional: cambiar contraseña (tu controlador valida "contraseña") --}}
          <div class="mb-2">
            <label for="edit_contrasena" class="form-label">Contraseña (opcional)</label>
            <input type="password" id="edit_contrasena" name="contraseña" class="form-control" placeholder="Deja vacío para no cambiar">
            <small class="text-muted">Mínimo 12 caracteres si decides cambiarla.</small>
          </div>

          {{-- Si tienes una columna "activo", puedes añadir este switch (si no, elimínalo) --}}
          {{-- 
          <div class="form-check mt-2">
            <input type="checkbox" id="edit_activo" name="activo" class="form-check-input">
            <label for="edit_activo" class="form-check-label">Activo</label>
          </div>
          --}}
        </div>

        <div class="modal-footer">
          {{-- Cancelar en ROJO como pediste --}}
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>
