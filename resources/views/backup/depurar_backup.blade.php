<div class="modal fade" id="depurar_backup" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="{{ route('backups.purge') }}" method="POST" id="formPurgarBackups">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title">Depurar respaldos antiguos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Eliminar archivos con antigüedad mayor o igual a:</label>
            <div class="input-group" style="max-width:260px">
              {{-- Solo informativo, no editable --}}
              <input type="number" class="form-control text-center bg-light"
                     id="purge_days_input"
                     name="days"
                     min="1"
                     readonly
                     value="{{ env('BACKUP_RETENTION_DAYS', 15) }}">
              <span class="input-group-text">días</span>
            </div>
          </div>
          <p class="mb-0 text-danger" id="purge_message">
            Se eliminarán todos los respaldos con antigüedad <strong>mayor o igual a {{ env('BACKUP_RETENTION_DAYS', 15) }} días.</strong>
          </p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">Sí, depurar</button>
        </div>
      </form>
    </div>
  </div>
</div>
