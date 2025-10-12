<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post" id="formEliminarMarca">
        @csrf
        @method('DELETE')

        <div class="modal-header">
          <h5 class="modal-title">Eliminar Marca</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="idMarca" id="eliminar_idmarca">
          <p id="eliminar_message">¿Estás seguro de eliminar esta marca?</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning" id="eliminar_submit_btn">Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>