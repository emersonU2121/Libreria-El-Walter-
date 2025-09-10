<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería El Walter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
        }
        .navbar {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .dropdown-menu a:hover {
            background-color: #3498db !important;
            color: white !important;
        }
        #contenido {
            min-height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* --- FIX scroll en #contenido (debe ir al final del <style>) --- */
:root { --nav-h: 64px; }                 /* ya lo tenías, se mantiene */
html, body { height: 100%; overflow: hidden; }

.navbar { position: sticky; top: 0; z-index: 1030; } /* menú visible siempre */

/* Solo #contenido scrollea y sin perder altura por el mt-5 */
#contenido {
  margin-top: 0 !important;                              /* anula el mt-5 del HTML */
  height: calc(100vh - var(--nav-h));                    /* alto válido */
  overflow-y: auto;                                      /* scroll vertical */
  -webkit-overflow-scrolling: touch;                     /* suave en móviles */
  align-items: flex-start;                               /* comienza arriba */
  padding: 16px 12px 32px;                               /* top y bottom para que se vean los botones */
}

        .compact-form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .compact-form h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 15px;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">
                <img src="{{ asset('images/W.png') }}" alt="Logo" width="40" height="40" class="me-2">
                Librería "El Walter"
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#">Marcas</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Categoría</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Compras</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Ventas</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="usuariosDropdown" 
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Usuarios
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="usuariosDropdown">
    <li><a class="dropdown-item" href="{{ route('usuarios.registrar') }}">Registrar usuario</a></li>
    <li><a class="dropdown-item" href="{{ route('usuarios.mostrar') }}">Mostrar usuarios</a></li>
</ul>

                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="perfilDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2">👤</span> 
                            <span>
                                @php $rol = "Invitado"; @endphp
                                {{ $rol }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="perfilDropdown">

                           @if(Auth::check())
                               <li>
                                   <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                       @csrf
                                       <button type="submit" class="dropdown-item">Cerrar sesión</button>
                                   </form>
                               </li>
                           @else
                               <li><a class="dropdown-item" href="{{ route('login') }}">Iniciar sesión</a></li>
                           @endif

                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido dinámico -->
    <div id="contenido" class="container mt-5">
        @yield('contenido')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
