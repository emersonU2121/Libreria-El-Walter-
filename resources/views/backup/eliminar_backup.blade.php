{{-- Modal eliminar backup --}}
<div class="modal fade" id="eliminar_backup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('backups.destroy') }}" method="POST" id="formEliminarBackup">
        @csrf
        <input type="hidden" name="path" id="delete_path">

        <div class="modal-header">
          <h5 class="modal-title">Confirmación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <p class="mb-0">
            ¿Estás seguro de eliminar el respaldo <strong id="eliminar_filename">archivo.sql</strong>?
          </p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Sí, eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>
