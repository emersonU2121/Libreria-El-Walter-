<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Librería "El Walter"</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
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

        .login-card h1 {
            font-size: 1.8rem;
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

        .text-center a {
            font-size: 0.9rem;
            color: #007bff;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h1>Iniciar Sesión</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="post" autocomplete="off">
            @csrf
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
               <input type="email" name="correo" id="correo" class="form-control" required autocomplete="off" value="{{ old('correo') }}">
            </div>

            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" name="contraseña" id="contraseña" class="form-control" required autocomplete="new-password">
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-dark">Ingresar</button>
            </div>

            <div class="mt-3 text-center">
                <a href="/password/reset">¿Olvidaste tu contraseña?</a>
            </div>
        </form>
    </div>

</body>
</html>



