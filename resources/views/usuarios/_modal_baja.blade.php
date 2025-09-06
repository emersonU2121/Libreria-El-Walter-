<div class="modal fade" id="modalBaja" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="#" method="post">
        @csrf
        {{-- @method('PUT') --}}
        <div class="modal-header">
          <h5 class="modal-title">Confirmación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" id="baja_user_id">
          <p id="baja_message">¿Estás seguro de dar de baja a este usuario?</p>
        </div>
      <div class="modal-footer">
  <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
  <button type="submit" class="btn btn-warning" id="baja_submit_btn">Sí, dar de baja</button>
</div>

      </form>
    </div>
  </div>
</div>
