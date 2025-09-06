@extends('menu')

@section('contenido')
<div class="compact-form">
    <h1>Registro de Usuario</h1>

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

    <form action="{{ route('usuarios.store') }}" method="post" autocomplete="off" novalidate>
        @csrf

        {{-- Señuelos anti-autocompletado --}}
        <input type="text" name="fakeuser" autocomplete="username" style="display:none">
        <input type="password" name="fakepass" autocomplete="current-password" style="display:none">

        <div class="mb-3">
            <label for="nombre" class="form-label">Usuario</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                   value="{{ old('nombre') }}" required data-no-autofill readonly>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" id="correo" name="correo" class="form-control"
                   value="{{ old('correo') }}" required data-no-autofill readonly>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            {{-- name con ñ porque tu Controller lo valida así --}}
            <input type="password" id="contrasena" name="contraseña" class="form-control"
                   required autocomplete="new-password" data-no-autofill readonly>
            <small class="text-muted">Mínimo 12 caracteres.</small>
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select id="rol" name="rol" class="form-select" required data-no-autofill readonly>
                <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Seleccione</option>
                <option value="Empleado" {{ old('rol')=='Empleado' ? 'selected':'' }}>Empleado</option>
                <option value="Administrador" {{ old('rol')=='Administrador' ? 'selected':'' }}>Administrador</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-dark">Crear Cuenta</button>
            <a href="{{ route('inicio') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>

{{-- Evitar autocompletado: quitar readonly al enfocar --}}
<script>
  document.querySelectorAll('[data-no-autofill]').forEach(el => {
    el.setAttribute('readonly', 'readonly');
    el.addEventListener('focus', () => el.removeAttribute('readonly'), { once: true });
  });
</script>
@endsection
