@extends('menu')

@section('contenido')
<div style="margin-right: 20px;" class="compact-form">
    <h1>Registro de Categorías</h1>

    @if(session('ok'))   <div class="alert alert-success">{{ session('ok') }}</div> @endif
    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div> @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.store') }}" method="post" autocomplete="off" novalidate id="formRegistro">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría</label>
            <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
            @error('nombre')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-dark">Registrar Categoría</button>
            <a href="{{ route('categorias.mostrarC') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>

{{-- Card del listado (mt-5 para despegar del navbar) --}}
<div style="margin: auto" class="card shadow mt-5 p-4 w-100">
  <h2 class="mb-4 text-center">Lista de Categorías</h2>

  {{-- Buscador superior --}}
  <div class="card-body">
      <form action="{{ route('categorias.mostrarC') }}" method="GET" class="row g-3 align-items-center">
          <div class="col-md-8">
              <div class="input-group">
                  <input
                      type="text"
                      name="q"
                      class="form-control"
                      placeholder="Buscar categorías por nombre..."
                      value="{{ request('q') }}"
                      aria-label="Buscar categorías">
                  <button type="submit" class="btn btn-primary">Buscar</button>
              </div>
          </div>
          <div class="col-md-4">
              @if(request('q'))
                  <div class="d-flex align-items-center">
                      <span class="text-muted me-2">Resultados para: "{{ request('q') }}"</span>
                      <a href="{{ route('categorias.mostrarC') }}" class="btn btn-outline-secondary btn-sm">Limpiar</a>
                  </div>
              @endif
          </div>
      </form>
  </div>

  @if($categorias->isEmpty())
    <div class="alert alert-warning text-center">No hay categorías registradas.</div>
  @else
    <div class="table-responsive">
      <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>Nombre</th>
            <th style="width:240px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categorias as $c)
            <tr>
              <td>{{ $c->nombre }}</td>
              <td class="d-flex gap-2 justify-content-center">
                {{-- Editar --}}
                <button
                  type="button"
                  class="btn btn-sm btn-primary btn-open-edit"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEditar"
                  data-idcategoria="{{ $c->idcategoria }}"
                  data-nombre="{{ $c->nombre }}"
                  data-update-url="{{ route('categorias.update', $c->idcategoria) }}"
                >Editar</button>

                {{-- Eliminar --}}
                <button
                  type="button"
                  class="btn btn-sm btn-danger btn-open-eliminar"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEliminar"
                  data-idcategoria="{{ $c->idcategoria }}"
                  data-nombre="{{ $c->nombre }}"
                  data-delete-url="{{ route('categorias.destroy', $c->idcategoria) }}"
                >Eliminar</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Paginación compacta con prev/next --}}
    <div class="d-flex justify-content-between align-items-center mt-3">
      <div class="text-muted small">
        Mostrando {{ $categorias->firstItem() }}–{{ $categorias->lastItem() }} de {{ $categorias->total() }}
      </div>
      <div>
        {!! $categorias->links('vendor.pagination.prev-next-only') !!}
      </div>
    </div>
  @endif
</div>

{{-- Modales (ajusta nombres si tus parciales se llaman distinto) --}}
@include('categorias._modal_editar')
@include('categorias._modal_baja')

{{-- === JS === --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  // EDITAR
  document.querySelectorAll('.btn-open-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit_idcategoria').value = btn.dataset.idcategoria;
      document.getElementById('edit_nombre').value = btn.dataset.nombre || '';
      const f = document.getElementById('formEditarCategoria');
      if (f) f.action = btn.dataset.updateUrl || '#';
    });
  });

  // ELIMINAR (click directo)
  document.querySelectorAll('.btn-open-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.idcategoria;
      const nombre = btn.dataset.nombre || '';
      const f = document.getElementById('formEliminarCategoria');
      const msg = document.getElementById('eliminar_message');
      const hid = document.getElementById('eliminar_idcategoria');

      if (hid) hid.value = id;
      if (msg) msg.innerHTML = `¿Estás seguro de eliminar la categoría <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
      if (f) f.action = btn.dataset.deleteUrl || '#';
    });
  });

  // ELIMINAR (fallback: show.bs.modal)
  const modalEliminarEl = document.getElementById('modalEliminar');
  if (modalEliminarEl) {
    modalEliminarEl.addEventListener('show.bs.modal', (ev) => {
      const btn = ev.relatedTarget; if (!btn) return;
      const id = btn.getAttribute('data-idcategoria');
      const nombre = btn.getAttribute('data-nombre') || '';
      const f = document.getElementById('formEliminarCategoria');
      const msg = document.getElementById('eliminar_message');
      const hid = document.getElementById('eliminar_idcategoria');

      if (hid) hid.value = id;
      if (msg) msg.innerHTML = `¿Estás seguro de eliminar la categoría <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
      if (f) f.action = btn.getAttribute('data-delete-url') || '#';
    });
  }

  // Salvaguarda: evitar submit sin action
  ['formEditarCategoria','formEliminarCategoria'].forEach(id => {
    const f = document.getElementById(id);
    if (f) f.addEventListener('submit', e => {
      if (!f.action || f.action.endsWith('#')) {
        e.preventDefault();
        alert('No se pudo determinar el destino del formulario.');
      }
    });
  });
});

// Validación AJAX del nombre al editar
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formEditarCategoria');
  if (!form) return;

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    form.querySelectorAll('.text-danger').forEach(el => el.remove());

    const nombreActual = document.getElementById('edit_nombre').value;
    const idcategoria  = document.getElementById('edit_idcategoria').value;

    const botonOriginal = document.querySelector('.btn-open-edit[data-idcategoria="' + idcategoria + '"]');
    if (!botonOriginal) { form.submit(); return; }

    const nombreOriginal = botonOriginal.dataset.nombre;
    if (nombreActual === nombreOriginal) { form.submit(); return; }

    fetch("{{ route('categorias.validar-nombre') }}", {
      method: 'POST',
      headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      body: JSON.stringify({ nombre: nombreActual, idcategoria })
    })
    .then(res => res.json())
    .then(data => {
      if (data.duplicado) {
        const err = document.createElement('div');
        err.className = 'text-danger small mt-1';
        err.textContent = 'La categoría ya ha sido registrada.';
        document.getElementById('edit_nombre').after(err);
      } else {
        form.submit();
      }
    })
    .catch(() => form.submit());
  });
});
</script>

@if ($errors->has('nombre'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
    modal.show();
  });
</script>
@endif

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

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formRegistro');
  if (!form) return;
  const nombreInput = document.getElementById('nombre');

  nombreInput.addEventListener('input', function() {
    if (nombreInput.value.trim() === '') nombreInput.classList.add('is-invalid');
    else nombreInput.classList.remove('is-invalid');
  });

  form.addEventListener('submit', function(e) {
    if (nombreInput.value.trim() === '') {
      e.preventDefault();
      nombreInput.classList.add('is-invalid');
      nombreInput.focus();
    }
  });
});
</script>
@endsection
