@extends('menu')

@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Respaldos del Sistema</h2>
        <a href="{{ route('inicio') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    {{-- ===== Generar respaldo ===== --}}
    <div class="card border-0 shadow-sm w-100 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Generar respaldo</h5>
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">
                Crea un archivo <strong>.sql</strong> que incluye la base de datos del sistema.
            </p>

            <div class="d-flex flex-wrap gap-3">
                <form action="{{ route('backups.generar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mode" value="save">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar SQL
                    </button>
                </form>

                <form action="{{ route('backups.generar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="mode" value="download">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Generar y descargar SQL
                    </button>
                </form>
            </div>

            <hr class="my-4">

            {{-- Disparador de modal de depuración --}}
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="input-group" style="max-width:520px">
                    <span class="input-group-text">Eliminar ≥</span>
                    <input type="number" id="purge_days_ui" class="form-control text-center"
                           style="max-width:120px;" min="1"
                           value="{{ env('BACKUP_RETENTION_DAYS', 15) }}">
                    <span class="input-group-text">días</span>
                    <button type="button" class="btn btn-outline-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#depurar_backup"
                            id="btn-open-purge">
                        <i class="fas fa-broom me-1"></i>Depurar respaldos antiguos
                    </button>
                </div>
            </div>

        
        </div>
    </div>

    {{-- ===== Respaldos guardados ===== --}}
    <div class="card border-0 shadow-sm w-100">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Respaldos guardados</h5>
        </div>

        <div class="card-body p-0">
            @if($files->isEmpty())
                <div class="p-3 text-muted text-center">
                    Aún no hay respaldos guardados.
                </div>
            @else
                <div class="table-responsive-md">
                    <table class="table table-hover mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th>Archivo</th>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th class="text-end">Tamaño</th>
                                <th class="text-end" style="min-width:260px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($files as $f)
                                @php
                                    $b64url = rtrim(strtr(base64_encode($f['storage_path']), '+/', '-_'), '=');
                                @endphp
                                <tr>
                                    <td class="align-middle">
                                        <i class="fas fa-database text-primary me-2"></i>{{ $f['name'] }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $f['created_at'] ? \Carbon\Carbon::parse($f['created_at'])->format('d/m/Y H:i') : '—' }}
                                    </td>
                                    <td class="align-middle">{{ $f['by'] ?? '—' }}</td>
                                    <td class="align-middle text-end">{{ number_format($f['size_kb'], 1) }} KB</td>
                                    <td class="align-middle text-end">
                                        <a href="{{ route('backups.descargar') }}?f={{ $b64url }}"
                                           class="btn btn-outline-primary btn-sm me-2">
                                            <i class="fas fa-download me-1"></i>Descargar
                                        </a>

                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm btn-open-delete"
                                                data-file="{{ $f['storage_path'] }}"
                                                data-name="{{ $f['name'] }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#eliminar_backup">
                                            <i class="fas fa-trash me-1"></i>Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ======= Modals separados ======= --}}
@include('backup.eliminar_backup')
@include('backup.depurar_backup')

<script>
document.addEventListener('click', function(e){
  const btn = e.target.closest('.btn-open-delete');
  if (!btn) return;
  document.getElementById('delete_path').value = btn.getAttribute('data-file');
  document.getElementById('eliminar_filename').textContent = btn.getAttribute('data-name');
});

document.getElementById('btn-open-purge')?.addEventListener('click', function(){
  const v = document.getElementById('purge_days_ui').value || 15;
  document.getElementById('purge_days_input').value = v;
  document.getElementById('purge_message').innerHTML =
    'Se eliminarán todos los respaldos con antigüedad <strong>mayor o igual a ' + v + ' días</strong>.';
});
</script>

<style>
.table th, .table td { vertical-align: middle !important; }
.btn { border-radius: 6px; }
.card { border-radius: 10px; }
.table-responsive-md { overflow-x: visible; }
@media (max-width: 767.98px) { .table-responsive-md { overflow-x: auto; } }
</style>
@endsection
