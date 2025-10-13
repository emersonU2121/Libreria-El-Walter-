<div class="modal fade" id="modalActivarProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="" method="post" id="formActivarProducto">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Activar Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="idproducto" id="activar_idproducto">
          <p>¿Está seguro que desea reactivar el producto: <strong id="activar_nombre"></strong>?</p>
          <p class="text-muted">El producto estará disponible para ventas nuevamente.</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Activar</button>
        </div>
      </form>
    </div>
  </div>
</div>