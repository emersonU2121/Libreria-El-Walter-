<div class="modal fade" id="modalBajaProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post" id="formBajaProducto">
        @csrf @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title">Confirmación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="idproducto" id="baja_idproducto">
          <p id="baja_message_prod">¿Estás seguro de dar de baja este producto?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning" id="baja_submit_btn_prod">Sí, dar de baja</button>
        </div>
      </form>
    </div>
  </div>
</div>