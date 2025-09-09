<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña</title>
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

        input {
            font-size: 0.95rem;
        }

        .mb-3 {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Nuevo password</h2>

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
