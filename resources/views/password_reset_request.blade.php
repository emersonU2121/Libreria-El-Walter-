<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recuperar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: #fff;
            padding: 2rem 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
        }

        .login-card h2 {
            font-size: 1.6rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
        }

        .alert {
            font-size: 0.95rem;
        }

        button {
            width: 100%;
        }

        input[type="email"] {
            font-size: 0.95rem;
        }
    </style>
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
