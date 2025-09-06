@extends('menu')

@section('contenido')
<div class="compact-form">
    <h1>Registro de Usuario</h1>

    <form action="#" method="post" autocomplete="off" novalidate>
        @csrf

        {{-- Campos señuelo ocultos para evitar autocompletado del navegador --}}
        <input type="text" name="fakeuser" autocomplete="username" style="display:none">
        <input type="password" name="fakepass" autocomplete="current-password" style="display:none">

        <div class="mb-3">
            <label for="username" class="form-label">Usuario</label>
            <input type="text" id="username" name="username" class="form-control" required data-no-autofill readonly>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" id="email" name="email" class="form-control" required data-no-autofill readonly>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password" data-no-autofill readonly>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Rol</label>
            <select id="role" name="role" class="form-select" required data-no-autofill readonly>
                <option value="" disabled selected>Seleccione</option>
                <option value="employee">Empleado</option>
                <option value="admin">Administrador</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-dark">Crear Cuenta</button>
            <a href="{{ route('inicio') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>

{{-- Script para quitar readonly al enfocar y así evitar sugerencias --}}
<script>
    document.querySelectorAll('[data-no-autofill]').forEach(el => {
        el.addEventListener('focus', () => el.removeAttribute('readonly'), { once: true });
    });
</script>
@endsection
