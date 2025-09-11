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

    <form action="{{ route('usuarios.store') }}" method="post" autocomplete="off" novalidate id="formRegistro">
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
            <input
                type="email"
                id="correo"
                name="correo"
                class="form-control"
                value="{{ old('correo') }}"
                required
                inputmode="email"
                placeholder="usuario@dominio.com"
                data-no-autofill
                readonly
                {{-- patrón (ayuda visual); la validación real la hacemos abajo en JS --}}
                pattern="^[^@\s]+@(gmail\.com|outlook\.com|hotmail\.com|(?:[A-Za-z0-9-]+\.)*ues\.edu\.sv)$"
                title="Solo se permiten: gmail.com, outlook.com, hotmail.com o ues.edu.sv"
            >
            <div class="invalid-feedback" id="correo_error">
                Solo se permiten correos de: <strong>gmail.com</strong>, <strong>outlook.com</strong>,
                <strong>hotmail.com</strong> o <strong>ues.edu.sv</strong>.
            </div>
        </div>

        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            {{-- name con ñ porque tu Controller lo valida así --}}
            <input type="password" id="contrassena" name="contraseña" class="form-control"
                   required autocomplete="new-password" data-no-autofill readonly>
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

{{-- Evitar autocompletado: quitar readonly al enfocar + VALIDACIÓN DE DOMINIO --}}
<script>
  // Quitar readonly al enfocar (anti-autofill)
  document.querySelectorAll('[data-no-autofill]').forEach(el => {
    el.setAttribute('readonly', 'readonly');
    el.addEventListener('focus', () => el.removeAttribute('readonly'), { once: true });
  });

  // Dominios permitidos exactos
  const allowedExact = ['gmail.com', 'outlook.com', 'hotmail.com'];
  // Aceptar ues.edu.sv y cualquier subdominio (alumno.ues.edu.sv, etc.)
  function isAllowedDomain(domain) {
    if (!domain) return false;
    domain = domain.toLowerCase();
    return allowedExact.includes(domain) || domain === 'ues.edu.sv' || domain.endsWith('.ues.edu.sv');
  }

  (function () {
    const form   = document.getElementById('formRegistro');
    const correo = document.getElementById('correo');

    // Validación en tiempo real
    correo.addEventListener('input', () => {
      const parts = correo.value.trim().split('@');
      const ok = parts.length === 2 && isAllowedDomain(parts[1]);
      correo.classList.toggle('is-invalid', !ok && correo.value.trim() !== '');
    });

    // Bloquear envío si no cumple
    form.addEventListener('submit', (e) => {
      const value = correo.value.trim();
      const parts = value.split('@');
      const ok = parts.length === 2 && isAllowedDomain(parts[1]);

      if (!ok) {
        e.preventDefault();
        correo.classList.add('is-invalid');
        correo.focus();
      } else {
        correo.classList.remove('is-invalid');
      }
    });
  })();

  (() => {
  const form = document.getElementById('formRegistro');
  if (!form) return;

  const $ = id => document.getElementById(id);
  const nombre = $('nombre');
  const correo = $('correo');
  const pass   = $('contrassena');   // <-- tu mismo id
  const rol    = $('rol');

  // Crea un <div.invalid-feedback> si no existe, justo después del campo
  function ensureErrorEl(el, preferId, defaultMsg='Campo obligatorio*') {
    let err = preferId ? document.getElementById(preferId) : null;
    if (!err) err = el.parentElement.querySelector('.invalid-feedback');
    if (!err) {
      err = document.createElement('div');
      err.className = 'invalid-feedback';
      err.textContent = defaultMsg;
      el.insertAdjacentElement('afterend', err);
    }
    return err;
  }

  // Para correo ya tienes #correo_error; para los otros los creamos si faltan
  const nombreErr = ensureErrorEl(nombre, 'nombre_error');
  const correoErr = ensureErrorEl(correo, 'correo_error', 'Correo inválido.');
  const passErr   = ensureErrorEl(pass,   'pass_error');
  const rolErr    = ensureErrorEl(rol,    'rol_error');

  const setInvalid = (el, errEl, msg) => { el.classList.add('is-invalid'); if (errEl && msg) errEl.textContent = msg; };
  const clearInvalid = (el) => el.classList.remove('is-invalid');

  function validateNombre() {
    const ok = nombre.value.trim().length > 0;
    ok ? clearInvalid(nombre) : setInvalid(nombre, nombreErr, 'Campo obligatorio*');
    return ok;
  }

  function validateCorreo() {
    const v = correo.value.trim();
    if (!v) { setInvalid(correo, correoErr, 'Campo obligatorio*'); return false; }
    // Usa tu patrón del atributo pattern (si existe)
    let ok = true;
    const pattern = correo.getAttribute('pattern');
    if (pattern) {
      const re = new RegExp(pattern, 'i');
      ok = re.test(v);
    } else {
      ok = /\S+@\S+\.\S+/.test(v);
    }
    ok ? clearInvalid(correo)
       : setInvalid(correo, correoErr, 'Solo se permiten: gmail.com, outlook.com, hotmail.com o ues.edu.sv');
    return ok;
  }

  function validatePass() {
    const v = pass.value || '';
    if (!v.trim()) { setInvalid(pass, passErr, 'Campo obligatorio*'); return false; }
    if (v.length < 12) { setInvalid(pass, passErr, 'Mínimo 12 caracteres.'); return false; }
    clearInvalid(pass); return true;
  }

  function validateRol() {
    const ok = !!rol.value;
    ok ? clearInvalid(rol) : setInvalid(rol, rolErr, 'Campo obligatorio*');
    return ok;
  }

  // Validación en vivo
  nombre && nombre.addEventListener('input', validateNombre);
  correo && correo.addEventListener('input', validateCorreo);
  pass   && pass.addEventListener('input',   validatePass);
  rol    && rol.addEventListener('change',  validateRol);

  // Validar al enviar
  form.addEventListener('submit', (e) => {
    const ok = [validateNombre(), validateCorreo(), validatePass(), validateRol()].every(Boolean);
    if (!ok) {
      e.preventDefault();
      const first = form.querySelector('.is-invalid');
      if (first) first.scrollIntoView({behavior: 'smooth', block: 'center'});
    }
  });
})();
</script>
@endsection