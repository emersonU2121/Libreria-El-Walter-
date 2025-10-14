@extends('menu')

@section('contenido')
<style>
 .pager-sm nav, .pager-sm ul, .pager-sm li, .pager-sm a, .pager-sm span {
  font-size: .85rem !important;
  line-height: 1.1 !important;
}
.pager-sm a, .pager-sm span { padding: .2rem .45rem !important; }

/* Ocultar flechas prev/next (funciona en Tailwind y Bootstrap) */
.pager-sm a[rel="prev"],
.pager-sm a[rel="next"],
.pager-sm span[aria-label="« Previous»"],
.pager-sm span[aria-label="Next »"] { display: none !important; }

/* Si tu paginador renderiza « » como texto */
.pager-sm li:first-child a, .pager-sm li:first-child span,
.pager-sm li:last-child  a, .pager-sm li:last-child  span {
  display: none !important;
}
  </style>
<div style="margin-right: 20px;" class="compact-form">
  <h1>Registro de Categorías</h1>

  @if(session('ok'))
  <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  <form action="{{ route('categorias.store') }}" method="post" autocomplete="off" novalidate id="formRegistro">
    @csrf

    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre de la Categoría</label>
      <input type="text" id="nombre" name="nombre" class="form-control"
        value="{{ old('nombre') }}" required>
      @error('nombre')
      <div class="text-danger small">{{ $message }}</div>
      @enderror
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-dark">Registrar Categoría</button>
      <a href="{{ route('categorias.mostrarC') }}" class="btn btn-danger">Cancelar</a>
    </div>
  </form>
</div>


<div style="margin: auto" class="card shadow p-4 w-100">
  <h2 class="mb-4 text-center">Lista de Categorías</h2>

  @if($categorias->isEmpty())
  <div class="alert alert-warning text-center">No hay categorías registradas.</div>
  @else
  {{-- Dentro de la card de "Lista de Categorías" --}}
  <div class="d-flex justify-content-center mb-3">
    <form class="search-bar" method="GET" action="{{ route('categorias.mostrarC') }}" role="search">
      <div class="search-pill shadow-sm">
        <input
          type="search"
          name="q"
          value="{{ $q }}"
          class="search-input"
          placeholder="Buscar categorías por nombre..."
          aria-label="Buscar">
        <button class="btn-search" type="submit">Buscar</button>
      </div>
      @if($q)
      <div class="text-center mt-1">
        <a class="btn btn-link p-0" href="{{ route('categorias.mostrarC') }}">Limpiar búsqueda</a>
      </div>
      @endif
    </form>
  </div>

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
              data-update-url="{{ route('categorias.update', $c->idcategoria) }}">Editar</button>

            {{-- Eliminar --}}
            <button
              type="button"
              class="btn btn-sm btn-danger btn-open-eliminar"
              data-bs-toggle="modal"
              data-bs-target="#modalEliminar"
              data-idcategoria="{{ $c->idcategoria }}"
              data-nombre="{{ $c->nombre }}"
              data-delete-url="{{ route('categorias.destroy', $c->idcategoria) }}">Eliminar</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
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

{{-- Modales --}}
@include('categorias._modal_editar')
@include('categorias._modal_baja')

{{-- JS: fija actions, textos y colores (doble seguro: click y show.bs.modal) --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // ==== EDITAR =====
    document.querySelectorAll('.btn-open-edit').forEach(btn => {
      btn.addEventListener('click', () => {
        document.getElementById('edit_idcategoria').value = btn.dataset.idcategoria;
        document.getElementById('edit_nombre').value = btn.dataset.nombre || '';
        const f = document.getElementById('formEditarCategoria');
        if (f) f.action = btn.dataset.updateUrl || '#';
      });
    });

    // ==== ELIMINAR (click directo) ====
    document.querySelectorAll('.btn-open-eliminar').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.idcategoria;
        const nombre = btn.dataset.nombre || '';
        const f = document.getElementById('formEliminarCategoria');
        const msg = document.getElementById('eliminar_message');
        const hid = document.getElementById('eliminar_idcategoria');

        if (hid) hid.value = id;
        if (msg) {
          msg.innerHTML = `¿Estás seguro de eliminar la categoría <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
        }
        if (f) f.action = btn.dataset.deleteUrl || '#';
      });
    });

    // ==== ELIMINAR (fallback: show.bs.modal) ====
    const modalEliminarEl = document.getElementById('modalEliminar');
    if (modalEliminarEl) {
      modalEliminarEl.addEventListener('show.bs.modal', (ev) => {
        const btn = ev.relatedTarget;
        if (!btn) return;
        const id = btn.getAttribute('data-idcategoria');
        const nombre = btn.getAttribute('data-nombre') || '';
        const f = document.getElementById('formEliminarCategoria');
        const msg = document.getElementById('eliminar_message');
        const hid = document.getElementById('eliminar_idcategoria');

        if (hid) hid.value = id;
        if (msg) {
          msg.innerHTML = `¿Estás seguro de eliminar la categoría <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
        }
        if (f) f.action = btn.getAttribute('data-delete-url') || '#';
      });
    }

    // Salvaguarda: evita POST incorrecto si faltara action
    ['formEditarCategoria', 'formEliminarCategoria'].forEach(id => {
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

  // Validación AJAX para nombre duplicado al editar
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEditarCategoria');

    if (form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();

        // 1. LIMPIAR ERRORES PREVIOS
        form.querySelectorAll('.text-danger').forEach(el => el.remove());

        // 2. OBTENER LOS VALORES ACTUALES
        const nombreActual = document.getElementById('edit_nombre').value;
        const idcategoria = document.getElementById('edit_idcategoria').value;

        // 3. BOTÓN ORIGINAL PARA OBTENER EL NOMBRE ORIGINAL
        const botonOriginal = document.querySelector('.btn-open-edit[data-idcategoria="' + idcategoria + '"]');
        if (!botonOriginal) {
          form.submit();
          return;
        }

        const nombreOriginal = botonOriginal.dataset.nombre;

        // 4. ¿CAMBIÓ EL NOMBRE?
        if (nombreActual === nombreOriginal) {
          form.submit();
        } else {
          // 5. VALIDAR CON AJAX SOLO SI CAMBIÓ
          fetch("{{ route('categorias.validar-nombre') }}", {
              method: 'POST',
              headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
              },
              body: JSON.stringify({
                nombre: nombreActual,
                idcategoria: idcategoria
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
            .catch(() => {
              form.submit();
            });
        }
      });
    }
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
      modalEditar.addEventListener('hide.bs.modal', function() {
        // Oculta los mensajes de error al cerrar el modal
        document.querySelectorAll('#modalEditar .text-danger').forEach(el => el.style.display = 'none');
      });
    }
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formRegistro');
    if (!form) return;

    const nombreInput = document.getElementById('nombre');

    // Validación en tiempo real
    nombreInput.addEventListener('input', function() {
      if (nombreInput.value.trim() === '') {
        nombreInput.classList.add('is-invalid');
      } else {
        nombreInput.classList.remove('is-invalid');
      }
    });

    // Validación al enviar
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