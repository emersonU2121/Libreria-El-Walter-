@extends('menu')

@section('contenido')
<div class="card shadow p-4 w-100">
  <h2 class="mb-4 text-center">Lista de Marcas</h2>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if($marcas->isEmpty())
    <div class="alert alert-warning text-center">No hay marcas registradas.</div>
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
          @foreach($marcas as $marca)
            <tr>
              <td>{{ $marca->nombre }}</td>
              <td class="d-flex gap-2 justify-content-center">
                {{-- Editar --}}
                <button
                  type="button"
                  class="btn btn-sm btn-primary btn-open-edit"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEditar"
                  data-idmarca="{{ $marca->idMarca }}"
                  data-nombre="{{ $marca->nombre }}"
                  data-update-url="{{ route('marcas.update', $marca->idMarca) }}"
                >Editar</button>

                {{-- Eliminar --}}
                <button
                  type="button"
                  class="btn btn-sm btn-danger btn-open-eliminar"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEliminar"
                  data-idmarca="{{ $marca->idMarca }}"
                  data-nombre="{{ $marca->nombre }}"
                  data-delete-url="{{ route('marcas.destroy', $marca->idMarca) }}"
                >Eliminar</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

{{-- Botón para agregar nueva marca --}}
<div class="text-end mt-3">
  <a href="{{ route('marcas.create') }}" class="btn btn-success">Agregar Nueva Marca</a>
</div>

{{-- Modales --}}
@include('marcas._modal_editar')
@include('marcas._modal_eliminar')

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

  // ==== EDITAR =====
  document.querySelectorAll('.btn-open-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit_idmarca').value = btn.dataset.idmarca;
      document.getElementById('edit_nombre').value = btn.dataset.nombre || '';
      const f = document.getElementById('formEditarMarca');
      if (f) f.action = btn.dataset.updateUrl || '#';
    });
  });

  // ==== ELIMINAR =====
  document.querySelectorAll('.btn-open-eliminar').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.idmarca;
      const nombre = btn.dataset.nombre || '';
      const f = document.getElementById('formEliminarMarca');
      const msg = document.getElementById('eliminar_message');
      const hid = document.getElementById('eliminar_idmarca');

      if (hid) hid.value = id;
      if (msg) {
        msg.innerHTML = `¿Estás seguro de eliminar la marca <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
      }
      if (f) f.action = btn.dataset.deleteUrl || '#';
    });
  });

  // Salvaguarda
  ['formEditarMarca','formEliminarMarca'].forEach(id => {
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formEditarMarca');
    
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      form.querySelectorAll('.text-danger').forEach(el => el.remove());

      const nombreActual = document.getElementById('edit_nombre').value;
      const idmarca = document.getElementById('edit_idmarca').value;
      
      const botonOriginal = document.querySelector('.btn-open-edit[data-idmarca="' + idmarca + '"]');
      
      if (!botonOriginal) {
        form.submit();
        return;
      }

      const nombreOriginal = botonOriginal.dataset.nombre;

      if (nombreActual === nombreOriginal) {
        form.submit();
      } else {
        fetch("{{ route('marcas.validar') }}", {
          method: 'POST',
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({ 
            nombre: nombreActual, 
            idMarca: idmarca 
          })
        })
        .then(res => res.json())
        .then(data => {
          if (data.duplicado) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-danger small mt-1';
            errorDiv.textContent = 'La marca ya ha sido registrada.';
            document.getElementById('edit_nombre').after(errorDiv);
          } else {
            form.submit();
          }
        })
        .catch(error => {
          form.submit();
        });
      }
    });
  }
});
</script>
@endsection