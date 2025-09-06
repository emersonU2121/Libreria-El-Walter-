<div class="modal fade" id="modalBaja" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      {{-- El action se fija desde JS al abrir el modal --}}
      <form action="" method="post" id="formBajaUsuario">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Confirmación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="idusuario" id="baja_idusuario">
          <p id="baja_message">¿Estás seguro de dar de baja a este usuario?</p>
        </div>

        <div class="modal-footer">
          {{-- Cancelar en ROJO (como pediste) --}}
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          {{-- Confirmación por defecto en AMARILLO; JS cambiará a verde si es reactivación --}}
          <button type="submit" class="btn btn-warning" id="baja_submit_btn">Sí, dar de baja</button>
        </div>
      </form>
    </div>
  </div>
</div>
