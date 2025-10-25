<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/password_reset_form.css') }}" rel="stylesheet">
  
</head>
<body>
    <div class="login-card">
        <h2>Nueva Contraseña</h2>

        <form method="POST" action="/password/reset">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label">Correo:</label>
                <input type="email" name="correo" class="form-control" value="{{ $correo }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nueva contraseña:</label>
                <input type="password" name="contraseña" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-dark">Cambiar contraseña</button>
        </form>
    </div>
</body>
</html>
