@extends('menu')

@section('contenido')
<div class="card shadow p-4 w-100">
    <h2 class="mb-4 text-center">Lista de Usuarios</h2>

    @if($usuarios->isEmpty())
        <div class="alert alert-warning text-center">
            No hay usuarios registrados.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th style="width: 240px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $u)
                        @php
                            // Si no tienes columna 'activo', mostramos "Activo" por defecto
                            $isActive = isset($u->activo) ? (bool)$u->activo : true;
                        @endphp
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
                            <td class="d-flex gap-2 justify-content-center">
                                {{-- Botón Editar: abre modal y pasa datos --}}
                                <button
                                    type="button"
                                    class="btn btn-sm btn-primary btn-open-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar"
                                    data-idusuario="{{ $u->idusuario }}"
                                    data-nombre="{{ $u->nombre }}"
                                    data-correo="{{ $u->correo }}"
                                    data-rol="{{ $u->rol ?? '' }}"
                                >
                                    Editar
                                </button>

                                {{-- Botón Dar de baja / Reactivar --}}
                                <button
                                    type="button"
                                    class="btn btn-sm {{ $isActive ? 'btn-warning' : 'btn-success' }} btn-open-baja"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBaja"
                                    data-idusuario="{{ $u->idusuario }}"
                                    data-nombre="{{ $u->nombre }}"
                                    data-activo="{{ $isActive ? 1 : 0 }}"
                                >
                                    {{ $isActive ? 'Dar de baja' : 'Reactivar' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Incluye los modales (usa las versiones con IDs en español) --}}
@include('usuarios._modal_editar') {{-- Debe tener inputs: edit_idusuario, edit_nombre, edit_correo, edit_rol, edit_contrasena (opcional) --}}
@include('usuarios._modal_baja')   {{-- Debe tener inputs: baja_idusuario, baja_message, baja_submit_btn --}}

{{-- Script para rellenar modales --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Modal Editar
    document.querySelectorAll('.btn-open-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit_idusuario').value = btn.dataset.idusuario;
            document.getElementById('edit_nombre').value    = btn.dataset.nombre || '';
            document.getElementById('edit_correo').value    = btn.dataset.correo || '';
            document.getElementById('edit_rol').value       = btn.dataset.rol || '';

            // Si conectas backend:
            // document.querySelector('#modalEditar form').action = `/usuarios/${btn.dataset.idusuario}`;
        });
    });

    // Modal Baja/Reactivar
    document.querySelectorAll('.btn-open-baja').forEach(btn => {
        btn.addEventListener('click', () => {
            const activo = btn.dataset.activo === '1';
            const id     = btn.dataset.idusuario;
            const nombre = btn.dataset.nombre || '';

            document.getElementById('baja_idusuario').value = id;
            document.getElementById('baja_message').innerHTML = activo
              ? `¿Estás seguro de dar de baja al usuario <strong>${nombre}</strong>?`
              : `¿Deseas reactivar al usuario <strong>${nombre}</strong>?`;

            const bajaBtn = document.getElementById('baja_submit_btn');
            bajaBtn.textContent = activo ? 'Sí, dar de baja' : 'Reactivar';
            bajaBtn.className   = activo ? 'btn btn-danger' : 'btn btn-success'; // Cancelar ya es rojo en el modal
            // Si conectas backend:
            // document.querySelector('#modalBaja form').action = `/usuarios/${id}/toggle-estado`;
        });
    });
});
</script>
@endsection
