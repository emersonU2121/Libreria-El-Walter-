<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/password_reset_request.css') }}" rel="stylesheet">
    
</head>
<body>
    <div class="login-card">
        <h2>Recuperar contraseña</h2>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->has('correo'))
    <div class="alert alert-danger">
        {{ $errors->first('correo') }}
    </div>
@endif

        <form method="POST" action="/password/email">
            @csrf
            <div class="mb-3">
                <label class="form-label">Correo:</label>
                <input type="email" name="correo" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-dark">Enviar enlace de recuperación</button>
        </form>
    </div>
</body>
</html>
