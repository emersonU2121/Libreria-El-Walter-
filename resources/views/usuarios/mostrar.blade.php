@extends('menu')



@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Lista de Usuarios</h2>
        <a href="{{ route('usuarios.registrar') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Registrar Nuevo Usuario
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Usuarios Registrados</h5>
        </div>
        <div class="card-body">
            @if($usuarios->isEmpty())
                <div class="alert alert-warning text-center">No hay usuarios registrados.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-sm text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-dark">ID</th>
                                <th class="text-dark">Nombre</th>
                                <th class="text-dark">Correo</th>
                                <th class="text-dark">Rol</th>
                                <th class="text-dark">Estado</th>
                                <th class="text-dark" style="width:240px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($usuarios as $u)
                            @php $isActive = isset($u->activo) ? (bool)$u->activo : true; @endphp
                            <tr>
                                <td>{{ $u->idusuario }}</td>
                                <td>{{ $u->nombre }}</td>
                                <td>{{ $u->correo }}</td>
                                <td>{{ $u->rol ?? 'Sin rol' }}</td>
                                <td>
                                    @if($isActive)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        {{-- Editar (Estilo 'outline' aplicado) --}}
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary btn-open-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar"
                                            data-idusuario="{{ $u->idusuario }}"
                                            data-nombre="{{ $u->nombre }}"
                                            data-correo="{{ $u->correo }}"
                                            data-rol="{{ $u->rol ?? '' }}"
                                            data-update-url="{{ route('usuarios.update', $u->idusuario) }}"
                                        >Editar</button>

                                      
                                        <button
                                            type="button"
                                            class="btn btn-sm {{ $isActive ? 'btn-outline-warning' : 'btn-outline-success' }} btn-open-baja"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalBaja"
                                            data-idusuario="{{ $u->idusuario }}"
                                            data-nombre="{{ $u->nombre }}"
                                            data-activo="{{ $isActive ? 1 : 0 }}"
                                            data-inactivar-url="{{ route('usuarios.inactivo', $u->idusuario) }}"
                                            data-activar-url="{{ route('usuarios.activo', $u->idusuario) }}"
                                        >{{ $isActive ? 'Dar baja' : 'Reactivar' }}</button>
                                    </div>
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
<button type="button" 
        class="btn btn-primary shadow" 
        id="btn-ayuda" 
        style="
            position: fixed; 
            bottom: 20px; 
            right: 20px; 
            z-index: 1050;
            width: 50px;         
            height: 50px;        
            border-radius: 50%;  
            font-size: 1.5rem;  
            font-weight: bold;   
            padding: 0;          
        ">
    ?
</button>


@include('usuarios._modal_editar')
@include('usuarios._modal_baja')



@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ==== EDITAR =====
    document.querySelectorAll('.btn-open-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit_idusuario').value = btn.dataset.idusuario;
            document.getElementById('edit_nombre').value   = btn.dataset.nombre || '';
            document.getElementById('edit_correo').value   = btn.dataset.correo || '';
            document.getElementById('edit_rol').value      = btn.dataset.rol || '';
            const f = document.getElementById('formEditarUsuario');
            if (f) f.action = btn.dataset.updateUrl || '#';
        });
    });

    // ==== BAJA / REACTIVAR (click directo) ====
    document.querySelectorAll('.btn-open-baja').forEach(btn => {
        btn.addEventListener('click', () => {
            const activo = btn.dataset.activo === '1';
            const id     = btn.dataset.idusuario;
            const nombre = btn.dataset.nombre || '';
            const f      = document.getElementById('formBajaUsuario');
            const msg    = document.getElementById('baja_message');
            const hid    = document.getElementById('baja_idusuario');
            const sBtn   = document.getElementById('baja_submit_btn');

            if (hid) hid.value = id;
            if (msg) {
                msg.innerHTML = activo
                ? `¿Estás seguro de dar de baja al usuario <strong>${nombre}</strong>?`
                : `¿Deseas reactivar al usuario <strong>${nombre}</strong>?`;
            }
            if (f) f.action = activo ? (btn.dataset.inactivarUrl || '#') : (btn.dataset.activarUrl || '#');

            // Colores / texto del botón confirmar
            if (sBtn) {
                sBtn.textContent = activo ? 'Sí, dar de baja' : 'Reactivar';
                sBtn.classList.remove('btn-success','btn-warning','btn-danger');
                sBtn.classList.add(activo ? 'btn-warning' : 'btn-success');
            }
        });
    });

    // ==== BAJA / REACTIVAR (fallback: show.bs.modal) ====
    const modalBajaEl = document.getElementById('modalBaja');
    if (modalBajaEl) {
        modalBajaEl.addEventListener('show.bs.modal', (ev) => {
            const btn = ev.relatedTarget;
            if (!btn) return;
            const activo = btn.getAttribute('data-activo') === '1';
            const id     = btn.getAttribute('data-idusuario');
            const nombre = btn.getAttribute('data-nombre') || '';
            const f      = document.getElementById('formBajaUsuario');
            const msg    = document.getElementById('baja_message');
            const hid    = document.getElementById('baja_idusuario');
            const sBtn   = document.getElementById('baja_submit_btn');

            if (hid) hid.value = id;
            if (msg) {
                msg.innerHTML = activo
                ? `¿Estás seguro de dar de baja al usuario <strong>${nombre}</strong>?`
                : `¿Deseas reactivar al usuario <strong>${nombre}</strong>?`;
            }
            if (f) f.action = activo ? (btn.getAttribute('data-inactivar-url') || '#')
                                      : (btn.getAttribute('data-activar-url') || '#');

            if (sBtn) {
                sBtn.textContent = activo ? 'Sí, dar de baja' : 'Reactivar';
                sBtn.classList.remove('btn-success','btn-warning','btn-danger');
                sBtn.classList.add(activo ? 'btn-warning' : 'btn-success');
            }
        });
    }

    // Salvaguarda: evita POST a /usuarios/mostrar si faltara action
    ['formEditarUsuario','formBajaUsuario'].forEach(id => {
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
@endpush

@push('scripts')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @push('scripts')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Caso con estructura "alert" (array con type/title/message) --}}
    @if(session('alert'))
        <script>
        (function () {
            const data = @json(session('alert'));
            Swal.fire({
                icon: data.type || 'info',
                title: data.title || 'Atención',
                text: data.message || '',
                confirmButtonText: 'Aceptar',
                allowOutsideClick: false,
                confirmButtonColor: '#3085d6'
            });
        })();
        </script>
    @endif

    {{-- Warning simple (string) --}}
    @if(session('warning') && !session('alert'))
        <script>
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: "{{ session('warning') }}", // texto plano con comillas
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false,
            confirmButtonColor: '#f0ad4e'
        });
        </script>
    @endif

    {{-- Ok / success simple (string) --}}
    @if(session('ok') && !session('alert'))
        <script>
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: "{{ session('ok') }}",
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false,
            confirmButtonColor: '#28a745'
        });
        </script>
    @endif

    {{-- Error simple (string) --}}
    @if(session('error') && !session('alert'))
        <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            confirmButtonText: 'Aceptar',
            allowOutsideClick: false,
            confirmButtonColor: '#d33'
        });
        </script>
    @endif
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const $ = (sel) => document.querySelector(sel);

  // Devuelve el primer botón de una clase dentro de la tabla
  function firstInTable(selector) {
    const table = $('#tabla-usuarios') || document;
    return table.querySelector(selector);
  }

  function highlight(el) {
    if (!el) return false;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    el.classList.add('help-pulse');
    setTimeout(() => el.classList.remove('help-pulse'), 1400);
    return true;
  }

  const btnAyuda = $('#btn-ayuda');
  if (!btnAyuda) return;

  btnAyuda.addEventListener('click', async () => {
    let i = 0;
    const steps = [
      {
        icon: 'question',
        title: 'Lista de Usuarios',
        html: `
          <div class="text-start">
            <p>Aquí puedes <b>editar, desactivar y reactivar</b> usuarios.</p>
            <ul class="mb-0">
              <li>No puedes <b>darte de baja a ti mismo</b>.</li>
              <li>Si un usuario es <b>Administrador</b>, no se permite dejar <b>0 admins activos</b>.</li>
            </ul>
          </div>`
      },
      {
        icon: 'info',
        title: 'Registrar nuevo usuario',
        html: `
          <div class="text-start">
            <p>Usa este botón para abrir el formulario de registro.</p>
          </div>`,
        onOpen: () => highlight($('#btn-registrar-usuario'))
      },
      {
        icon: 'info',
        title: 'Editar usuario',
        html: `
          <div class="text-start">
            <p>En cada fila, <b>Editar</b> abre el modal para actualizar datos.</p>
          </div>`,
        onOpen: () => highlight(firstInTable('.btn-open-edit'))
      },
      {
        icon: 'warning',
        title: 'Dar baja / Reactivar',
        html: `
          <div class="text-start">
            <ul class="mb-0">
              <li><b>Dar baja</b> cambia el estado a Inactivo.</li>
              <li><b>Reactivar</b> vuelve a Activo.</li>
              <li>No puedes <b>darte de baja</b> a ti mismo.</li>
              <li>No se permite desactivar al <b>último Administrador activo</b>.</li>
            </ul>
          </div>`,
        onOpen: () => highlight(firstInTable('.btn-open-baja'))
      },
      {
        icon: 'info',
        title: 'Estados',
        html: `
          <div class="text-start">
            <p>La columna <b>Estado</b> muestra si el usuario está Activo o Inactivo.</p>
          </div>`,
        onOpen: () => highlight($('#tabla-usuarios'))
      }
    ];

    const modal = Swal.mixin({
      showCancelButton: false,
      focusConfirm: true,
      confirmButtonText: 'Siguiente',
      confirmButtonColor: '#3085d6',
      width: 600,
      allowOutsideClick: false,
      didOpen: () => {
        const s = steps[i];
        if (s && typeof s.onOpen === 'function') setTimeout(s.onOpen, 50);
      }
    });

    for (i = 0; i < steps.length; i++) {
      await modal.fire({
        icon: steps[i].icon,
        title: steps[i].title,
        html: steps[i].html,
        confirmButtonText: i === steps.length - 1 ? 'Entendido' : 'Siguiente'
      });
    }
  });
});
</script>

<style>
/* Efecto de resalte */
.help-pulse {
  box-shadow: 0 0 0 0 rgba(49,132,253,.5);
  animation: help-pulse 1.4s ease-out 1;
  outline: 2px solid rgba(49,132,253,.35);
  border-radius: 6px;
}
@keyframes help-pulse {
  0%   { box-shadow: 0 0 0 0 rgba(49,132,253,.5); }
  70%  { box-shadow: 0 0 0 12px rgba(49,132,253,0); }
  100% { box-shadow: 0 0 0 0 rgba(49,132,253,0); }
}
</style>
@endpush
