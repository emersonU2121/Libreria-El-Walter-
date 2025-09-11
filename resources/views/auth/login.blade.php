<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Iniciar Sesión - Librería "El Walter"</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body{
      background-color:#f5f7fa;
      display:flex; align-items:center; justify-content:center;
      height:100vh;
      font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .login-card{
      background:#fff; padding:2rem 2.5rem; border-radius:12px;
      box-shadow:0 4px 20px rgba(0,0,0,0.1);
      width:100%; max-width:420px;
    }
    .login-card h1{ font-size:1.8rem; font-weight:600; margin-bottom:1.5rem; text-align:center; }
    .form-label{ font-weight:500; }
    .alert{ font-size:.95rem; }
    .text-center a{ font-size:.9rem; color:#007bff; text-decoration:none; }
    .text-center a:hover{ text-decoration:underline; }
  </style>
</head>
<body>

  <div class="login-card">
    <h1>Iniciar Sesión</h1>

    {{-- Mensaje principal de login (bloqueo o error de credenciales) --}}
    @if ($errors->has('login'))
      <div class="alert alert-danger mb-3">
        {{ $errors->first('login') }}
      </div>
    @elseif ($errors->any())
      <div class="alert alert-danger mb-3">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('login.post') }}" method="post" autocomplete="off" id="loginForm">
      @csrf

      <div class="mb-3">
        <label for="correo" class="form-label">Correo Electrónico</label>
        <input
          type="email"
          name="correo"
          id="correo"
          class="form-control"
          required
          autocomplete="off"
          value="{{ old('correo') }}"
          @if(session()->has('lock_seconds')) disabled @endif
        >
      </div>

      <div class="mb-3">
        <label for="contraseña" class="form-label">Contraseña</label>
        <input
          type="password"
          name="contraseña"
          id="contraseña"
          class="form-control"
          required
          autocomplete="new-password"
          @if(session()->has('lock_seconds')) disabled @endif
        >
      </div>

      <div class="d-grid mt-4">
        <button
          type="submit"
          class="btn btn-dark"
          id="loginBtn"
          @if(session()->has('lock_seconds')) disabled @endif
        >
          Ingresar
        </button>
      </div>

      @if(session()->has('lock_seconds'))
        <div class="mt-2 small text-muted">
          Espera <span id="countdown">{{ session('lock_seconds') }}</span> s para volver a intentar.
        </div>
      @endif

      <div class="mt-3 text-center">
        <a href="/password/reset">¿Olvidaste tu contraseña?</a>
      </div>
    </form>
  </div>

  @if(session()->has('lock_seconds'))
  <script>
    (function(){
      let s = (session('lock_seconds', 0));
      const cd  = document.getElementById('countdown');
      const em  = document.getElementById('correo');
      const pwd = document.getElementById('contraseña');
      const btn = document.getElementById('loginBtn');

      const tick = () => {
        s = Math.max(0, s - 1);
        if (cd) cd.textContent = s;
        if (s <= 0) {
          if (em)  em.removeAttribute('disabled');
          if (pwd) pwd.removeAttribute('disabled');
          if (btn) btn.removeAttribute('disabled');
          return;
        }
        setTimeout(tick, 1000);
      };
      setTimeout(tick, 1000);
    })();
  </script>
  @endif
</body>
</html>
