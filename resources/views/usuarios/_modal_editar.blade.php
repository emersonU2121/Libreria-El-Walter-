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
  <input type="text" id="edit_nombre" name="nombre" class="form-control" required value="{{ old('nombre') }}">
  @if ($errors->has('nombre'))
    <div class="text-danger small">
      {{ $errors->first('nombre') }}
    </div>
  @endif
</div>

<div class="mb-3">
  <label for="edit_correo" class="form-label">Correo</label>
  <input type="email" id="edit_correo" name="correo" class="form-control" required value="{{ old('correo') }}">
  @if ($errors->has('correo'))
    <div class="text-danger small">
      {{ $errors->first('correo') }}
    </div>
  @endif
</div>

<div class="mb-3">
  <label for="edit_rol" class="form-label">Rol</label>
  <select id="edit_rol" name="rol" class="form-select">
    <option value="" {{ old('rol')=='' ? 'selected' : '' }}>Sin rol</option>
    <option value="Administrador" {{ old('rol')=='Administrador' ? 'selected' : '' }}>Administrador</option>
    <option value="Empleado" {{ old('rol')=='Empleado' ? 'selected' : '' }}>Empleado</option>
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
