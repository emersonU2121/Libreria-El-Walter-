@extends('menu')

@section('contenido')
<div class="card shadow p-4 w-100">
    <h2 class="mb-4 text-center">Lista de Usuarios</h2>

    {{-- Datos ficticios de prueba --}}
    @php
    $usuarios = collect([
        (object)[ 'id' => 1, 'name' => 'Juan Pérez', 'email' => 'juan@example.com', 'role' => 'Administrador', 'activo' => true, 'created_at' => now(), 'updated_at' => now() ],
        (object)[ 'id' => 2, 'name' => 'María Gómez', 'email' => 'maria@example.com', 'role' => 'Empleado', 'activo' => true, 'created_at' => now(), 'updated_at' => now() ],
        (object)[ 'id' => 3, 'name' => 'Carlos Torres', 'email' => 'carlos@example.com', 'role' => null, 'activo' => false, 'created_at' => now(), 'updated_at' => now() ],
    ]);
    @endphp

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Correo Electrónico</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Creado</th>
                    <th>Actualizado</th>
                    <th style="width: 240px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->role ?? 'Sin rol' }}</td>
                        <td>
                            @if($u->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $u->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="d-flex gap-2 justify-content-center">
                            {{-- Botón Editar --}}
                            <button type="button"
                                    class="btn btn-sm btn-primary btn-open-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar"
                                    data-id="{{ $u->id }}"
                                    data-name="{{ $u->name }}"
                                    data-email="{{ $u->email }}"
                                    data-role="{{ $u->role ?? '' }}"
                                    data-activo="{{ $u->activo ? 1 : 0 }}">
                                Editar
                            </button>

                            {{-- Botón Dar de baja / Reactivar --}}
                            <button type="button"
                                    class="btn btn-sm {{ $u->activo ? 'btn-warning' : 'btn-success' }} btn-open-baja"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBaja"
                                    data-id="{{ $u->id }}"
                                    data-name="{{ $u->name }}"
                                    data-activo="{{ $u->activo ? 1 : 0 }}">
                                {{ $u->activo ? 'Dar de baja' : 'Reactivar' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Incluir modales --}}
@include('usuarios._modal_editar')
@include('usuarios._modal_baja')

{{-- Script para rellenar modales --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Modal Editar
    document.querySelectorAll('.btn-open-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('edit_user_id').value = btn.dataset.id;
            document.getElementById('edit_name').value = btn.dataset.name;
            document.getElementById('edit_email').value = btn.dataset.email;
            document.getElementById('edit_role').value = btn.dataset.role;
            document.getElementById('edit_activo').checked = btn.dataset.activo === '1';
        });
    });

    // Modal Baja/Reactivar
    document.querySelectorAll('.btn-open-baja').forEach(btn => {
        btn.addEventListener('click', () => {
            const activo = btn.dataset.activo === '1';
            document.getElementById('baja_user_id').value = btn.dataset.id;
            document.getElementById('baja_message').innerHTML = activo
              ? `¿Estás seguro de dar de baja al usuario <strong>${btn.dataset.name}</strong>?`
              : `¿Deseas reactivar al usuario <strong>${btn.dataset.name}</strong>?`;

            const bajaBtn = document.getElementById('baja_submit_btn');
            bajaBtn.textContent = activo ? 'Sí, dar de baja' : 'Reactivar';
            bajaBtn.className = activo ? 'btn btn-warning' : 'btn btn-success';
        });
    });
});
</script>
@endsection
