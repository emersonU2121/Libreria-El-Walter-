@extends('menu')

@section('contenido')
<div class="card shadow p-4 w-100">
  <h2 class="mb-4 text-center">Lista de Categorías</h2>

  @if($categorias->isEmpty())
    <div class="alert alert-warning text-center">No hay categorías registradas.</div>
  @else
    <div class="table-responsive">
      <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>Categoría</th>
            {{-- Si usas columna "estado" (bool) la mostramos --}}
            <th>Estado</th>
            <th style="width:240px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categorias as $c)
            @php
              // si no tienes "estado", la tratamos como activa por defecto
              $isActive = isset($c->estado) ? (bool)$c->estado : true;
            @endphp
            <tr>
              <td>{{ $c->nombre }}</td>

              <td>
                @if($isActive)
                  <span class="badge bg-success">Activo</span>
                @else
                  <span class="badge bg-secondary">Inactivo</span>
                @endif
              </td>

              <td class="d-flex gap-2 justify-content-center">
                {{-- Editar (abre modal) --}}
                <button
                  type="button"
                  class="btn btn-sm btn-primary btn-open-edit-cat"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEditar"
                  data-idcategoria="{{ $c->idcategoria }}"
                  data-nombre="{{ $c->nombre }}"
                  data-update-url="{{ route('categorias.update', $c->idcategoria) }}"
                >Editar</button>

                {{-- Dar de baja / Reactivar (abre modal) --}}
                <button
                  type="button"
                  class="btn btn-sm {{ $isActive ? 'btn-warning' : 'btn-success' }} btn-open-baja-cat"
                  data-bs-toggle="modal"
                  data-bs-target="#modalBaja"
                  data-idcategoria="{{ $c->idcategoria }}"
                  data-nombre="{{ $c->nombre }}"
                  data-activo="{{ $isActive ? 1 : 0 }}"
                  data-inactivar-url="{{ route('categorias.inactivo', $c->idcategoria) }}"
                  data-activar-url="{{ route('categorias.activo',   $c->idcategoria) }}"
                >{{ $isActive ? 'Dar de baja' : 'Reactivar' }}</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

{{-- Modales específicos de categorías --}}
@include('categorias._modal_editar')
@include('categorias._modal_baja')

{{-- Si hubo error de validación en nombre, reabrimos el modal de edición --}}
@if ($errors->has('nombre'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
    modal.show();
  });
</script>
@endif

{{-- Limpia errores del modal al cerrarlo (igual que usuarios) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modalEditar = document.getElementById('modalEditar');
  if (modalEditar) {
    modalEditar.addEventListener('hide.bs.modal', function () {
      document.querySelectorAll('#modalEditar .text-danger').forEach(el => el.style.display = 'none');
    });
  }
});
</script>

{{-- JS: fija actions, textos y colores (click + show.bs.modal), versión CATEGORÍAS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  // ==== EDITAR (click directo) ====
  document.querySelectorAll('.btn-open-edit-cat').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit_idcategoria').value = btn.dataset.idcategoria;
      document.getElementById('edit_nombre').value      = btn.dataset.nombre || '';
      const f = document.getElementById('formEditarCategoria');
      if (f) f.action = btn.dataset.updateUrl || '#';
    });
  });

  // ==== BAJA / REACTIVAR (click directo) ====
  document.querySelectorAll('.btn-open-baja-cat').forEach(btn => {
    btn.addEventListener('click', () => {
      const activo = btn.dataset.activo === '1';
      const id     = btn.dataset.idcategoria;
      const nombre = btn.dataset.nombre || '';
      const f      = document.getElementById('formBajaCategoria');
      const msg    = document.getElementById('baja_message');
      const hid    = document.getElementById('baja_idcategoria');
      const sBtn   = document.getElementById('baja_submit_btn');

      if (hid) hid.value = id;
      if (msg) {
        msg.innerHTML = activo
          ? `¿Estás seguro de dar de baja la categoría <strong>${nombre}</strong>?`
          : `¿Deseas reactivar la categoría <strong>${nombre}</strong>?`;
      }
      if (f) f.action = activo ? (btn.dataset.inactivarUrl || '#') : (btn.dataset.activarUrl || '#');

      if (sBtn) {
        sBtn.textContent = activo ? 'Sí, dar de baja' : 'Reactivar';
        sBtn.classList.remove('btn-success','btn-warning','btn-danger');
        sBtn.classList.add(activo ? 'btn-warning' : 'btn-success');
      }
    });
  });

  // ==== BAJA / REACTIVAR (fallback show.bs.modal) ====
  const modalBajaEl = document.getElementById('modalBaja');
  if (modalBajaEl) {
    modalBajaEl.addEventListener('show.bs.modal', (ev) => {
      const btn = ev.relatedTarget;
      if (!btn) return;
      const activo = btn.getAttribute('data-activo') === '1';
      const id     = btn.getAttribute('data-idcategoria');
      const nombre = btn.getAttribute('data-nombre') || '';
      const f      = document.getElementById('formBajaCategoria');
      const msg    = document.getElementById('baja_message');
      const hid    = document.getElementById('baja_idcategoria');
      const sBtn   = document.getElementById('baja_submit_btn');

      if (hid) hid.value = id;
      if (msg) {
        msg.innerHTML = activo
          ? `¿Estás seguro de dar de baja la categoría <strong>${nombre}</strong>?`
          : `¿Deseas reactivar la categoría <strong>${nombre}</strong>?`;
      }
      if (f) f.action = activo ? (btn.getAttribute('data-inactivar-url') || '#')
                               : (btn.getAttribute('data-activar-url')   || '#');

      if (sBtn) {
        sBtn.textContent = activo ? 'Sí, dar de baja' : 'Reactivar';
        sBtn.classList.remove('btn-success','btn-warning','btn-danger');
        sBtn.classList.add(activo ? 'btn-warning' : 'btn-success');
      }
    });
  }

  // Salvaguarda: evita POST sin action
  ['formEditarCategoria','formBajaCategoria'].forEach(id => {
    const f = document.getElementById(id);
    if (f) {
      f.addEventListener('submit', e => {
        if (!f.action || f.action.endsWith('#')) {
          e.preventDefault();
          alert('No se pudo determinar el destino del formulario.');
        }
      });
    }
  });
});
</script>

{{-- Validación AJAX del nombre (solo si cambia), igual que usuarios --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formEditarCategoria');
  if (!form) return;

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    // 1) limpiar errores previos
    form.querySelectorAll('.text-danger').forEach(el => el.remove());

    // 2) valores actuales
    const nombreActual = document.getElementById('edit_nombre')?.value ?? '';
    const idCategoria  = document.getElementById('edit_idcategoria')?.value ?? '';

    // 3) buscar botón original para obtener nombre original
    const botonOriginal = document.querySelector('.btn-open-edit-cat[data-idcategoria="' + idCategoria + '"]');
    if (!botonOriginal) { form.submit(); return; }

    const nombreOriginal = botonOriginal.dataset.nombre ?? '';

    // 4) si no cambió, enviar normal
    if (nombreActual === nombreOriginal) { form.submit(); return; }

    // 5) validar con AJAX
    fetch("{{ route('categorias.validar-nombre') }}", {
      method: 'POST',
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify({
        nombre: nombreActual,
        idcategoria: idCategoria
      })
    })
    .then(res => res.json())
    .then(data => {
      if (data.duplicado) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-danger small mt-1';
        errorDiv.textContent = 'La categoría ya ha sido registrada.';
        document.getElementById('edit_nombre').after(errorDiv);
      } else {
        form.submit();
      }
    })
    .catch(() => form.submit());
  });
});
</script>
@endsection
