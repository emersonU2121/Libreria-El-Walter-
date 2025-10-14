@extends('menu')

@section('contenido')
<div style="margin-right: 20px; "  class="compact-form">
    <h1>Registro de Marcas</h1>

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

    <form action="{{ route('marcas.store') }}" method="post" autocomplete="off" novalidate id="formRegistro">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Marca</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                   value="{{ old('nombre') }}" required>
            @error('nombre')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-dark">Registrar Marca</button>
            <a href="{{ route('marcas.index') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>




<div style="margin: auto" class="card shadow mt-5 p-4 w-100">


  <h2 class="mb-4 text-center">Lista de Marcas</h2>


  <div class="card-body">
        <form action="{{ route('marcas.mostrar') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" 
                           name="buscar" 
                           class="form-control" 
                           placeholder="Buscar marcas por nombre..." 
                           value="{{ request('buscar') }}"
                           aria-label="Buscar marcas">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
            <div class="col-md-4">
                @if(request('buscar'))
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">
                            Resultados para: "{{ request('buscar') }}"
                        </span>
                        <a href="{{ route('marcas.mostrar') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i> Limpiar
                        </a>
                    </div>
                @endif
            </div>
        </form>
    </div>

    
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
          @foreach($marcas as $m)
            <tr>

              <td>{{ $m->nombre }}</td>
              <td class="d-flex gap-2 justify-content-center">
                {{-- Editar --}}
                <button
                  type="button"
                  class="btn btn-sm btn-primary btn-open-edit"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEditar"
                  data-idmarca="{{ $m->idmarca }}"
                  data-nombre="{{ $m->nombre }}"
                  data-update-url="{{ route('marcas.update', $m->idmarca) }}"
                >Editar</button>

                {{-- Eliminar --}}
                <button
                  type="button"
                  class="btn btn-sm btn-danger btn-open-eliminar"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEliminar"
                  data-idmarca="{{ $m->idmarca }}"
                  data-nombre="{{ $m->nombre }}"
                  data-delete-url="{{ route('marcas.destroy', $m->idmarca) }}"
                >Eliminar</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

{{-- Modales --}}
@include('marcas._modal_editar_marca')
@include('marcas._modal_baja_marca') 

{{-- JS: fija actions, textos y colores (doble seguro: click y show.bs.modal) --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  // ==== EDITAR =====
  document.querySelectorAll('.btn-open-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit_idmarca').value = btn.dataset.idmarca;
      document.getElementById('edit_nombre').value = btn.dataset.nombre || '';
      const f = document.getElementById('formEditarMarca');
      if (f) f.action = btn.dataset.updateUrl || '#';
    });
  });

  // ==== ELIMINAR (click directo) ====
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

  // ==== ELIMINAR (fallback: show.bs.modal) ====
  const modalEliminarEl = document.getElementById('modalEliminar');
  if (modalEliminarEl) {
    modalEliminarEl.addEventListener('show.bs.modal', (ev) => {
      const btn = ev.relatedTarget;
      if (!btn) return;
      const id = btn.getAttribute('data-idmarca');
      const nombre = btn.getAttribute('data-nombre') || '';
      const f = document.getElementById('formEliminarMarca');
      const msg = document.getElementById('eliminar_message');
      const hid = document.getElementById('eliminar_idmarca');

      if (hid) hid.value = id;
      if (msg) {
        msg.innerHTML = `¿Estás seguro de eliminar la marca <strong>${nombre}</strong>? Esta acción no se puede deshacer.`;
      }
      if (f) f.action = btn.getAttribute('data-delete-url') || '#';
    });
  }

  // Salvaguarda: evita POST incorrecto si faltara action
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

// Validación AJAX para nombre duplicado al editar
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formEditarMarca');
    
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      // 1. LIMPIAR ERRORES PREVIOS
      form.querySelectorAll('.text-danger').forEach(el => el.remove());

      // 2. OBTENER LOS VALORES ACTUALES
      const nombreActual = document.getElementById('edit_nombre').value;
      const idmarca = document.getElementById('edit_idmarca').value;
      
      console.log('Nombre actual:', nombreActual);
      console.log('ID marca:', idmarca);

      // 3. BUSCAR EL BOTÓN ORIGINAL PARA OBTENER EL NOMBRE ORIGINAL
      const botonOriginal = document.querySelector('.btn-open-edit[data-idmarca="' + idmarca + '"]');
      
      if (!botonOriginal) {
        console.error('No se encontró el botón original');
        form.submit();
        return;
      }

      const nombreOriginal = botonOriginal.dataset.nombre;
      console.log('Nombre original:', nombreOriginal);

      // 4. COMPARAR: ¿EL NOMBRE CAMBIÓ?
      if (nombreActual === nombreOriginal) {
        console.log('Nombre no cambió - Enviando formulario directamente');
        form.submit();
      } else {
        console.log('Nombre cambió - Validando con AJAX...');
        
        // 5. VALIDAR CON AJAX SOLO SI CAMBIÓ
        fetch("{{ route('marcas.validar') }}", {
          method: 'POST',
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          body: JSON.stringify({ 
            nombre: nombreActual, 
            idmarca: idmarca 
          })
        })
        .then(res => {
          console.log('Respuesta del servidor recibida');
          return res.json();
        })
        .then(data => {
          console.log('Datos recibidos:', data);
          
          if (data.duplicado) {
            console.log('Nombre duplicado encontrado');
            // Mostrar error debajo del input
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-danger small mt-1';
            errorDiv.textContent = 'La marca ya ha sido registrada.';
            document.getElementById('edit_nombre').after(errorDiv);
          } else {
            console.log('Nombre disponible - Enviando formulario');
            form.submit();
          }
        })
        .catch(error => {
          console.error('Error en la validación:', error);
          console.log('Enviando formulario a pesar del error');
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
    modalEditar.addEventListener('hide.bs.modal', function () {
      // Oculta los mensajes de error al cerrar el modal
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