<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería El Walter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="{{ asset('css/menu.css') }}" rel="stylesheet">
</head>
<body>
   <nav class="navbar navbar-expand-lg navbar-dark navbar-transparent px-3">
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
            <!-- NUEVO MENÚ CON TUS BOTONES Y VALIDACIONES -->
            <div class="input me-auto">
                <!-- BOTONES BÁSICOS (SIEMPRE VISIBLES) -->
                <a href="#" class="value text-decoration-none">
                    <svg class="normal" viewBox="0 0 24 24">
                        <path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z" fill="currentColor"/>
                    </svg>
                    Marcas
                </a>
                
                <a href="#" class="value text-decoration-none">
                    <svg class="normal" viewBox="0 0 24 24">
                        <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z" fill="currentColor"/>
                    </svg>
                    Productos
                </a>
                
                <a href="#" class="value text-decoration-none">
                    <svg class="normal" viewBox="0 0 24 24">
                        <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z" fill="currentColor"/>
                    </svg>
                    Categoría
                </a>
                
                <a href="#" class="value text-decoration-none">
                    <svg class="normal" viewBox="0 0 24 24">
                        <path d="M17 18c-1.11 0-2 .89-2 2a2 2 0 002 2 2 2 0 000-4zM7 18c-1.11 0-2 .89-2 2a2 2 0 002 2 2 2 0 000-4zM7.17 14.75L7.2 14.63 8.1 13h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4h-.01l-1.1 2-2.76 5H8.53l-.13-.27L6.16 6l-.95-2-.94-2H1v2h2l3.6 7.59-1.35 2.44c-.73 1.34.23 2.97 1.75 2.97h12v-2H7.42c-.13 0-.25-.11-.25-.25z" fill="currentColor"/>
                    </svg>
                    Compras
                </a>
                
                <a href="#" class="value text-decoration-none">
                    <svg class="normal" viewBox="0 0 24 24">
                        <path d="M2 17h2v.5H3v1h1v.5H2v1h3v-4H2v1zm1-9h1V4H2v1h1v3zm-1 3h1.8L2 13.1v.9h3v-1H3.2L5 10.9V10H2v1zm5-6v2h14V5H7zm0 14h14v-2H7v2zm0-6h14v-2H7v2z" fill="currentColor"/>
                    </svg>
                    Ventas
                </a>
                
                <!-- MENÚ USUARIOS (SOLO PARA ADMINISTRADORES) -->
                @auth
                    @if(Auth::user()->rol === 'Administrador')
                    <div class="dropdown">
                        <a class="value text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="normal" viewBox="0 0 24 24">
                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A3.02 3.02 0 0016.95 6h-2.2c-.79 0-1.53.43-1.92 1.13L10.5 16H7v6h13zM4.5 11c-.83 0-1.5-.67-1.5-1.5S3.67 8 4.5 8s1.5.67 1.5 1.5S5.33 11 4.5 11zM7 20v-6H4v6h3z" fill="currentColor"/>
                            </svg>
                            Usuarios
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('usuarios.registrar') }}">Registrar usuario</a></li>
                            <li><a class="dropdown-item" href="{{ route('usuarios.mostrar') }}">Mostrar usuarios</a></li>
                        </ul>
                    </div>
                    @endif
                @endauth

                {{-- === PRODUCTOS (dropdown) === --}}
<div class="dropdown d-inline-block">
  <a class="value text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <svg class="normal" viewBox="0 0 24 24" style="width:20px;height:20px;vertical-align:middle;">
      <path d="M16 6V4H8v2H2v2h2l2.6 9.59A2 2 0 008.53 20H15.5a2 2 0 001.93-1.49L20 8h2V6h-6zM9 6h6v2H9V6zm6.5 12h-7l-2-8h11l-2 8z" fill="currentColor"/>
    </svg>
    Productos
  </a>
  <ul class="dropdown-menu">
    <li><a class="dropdown-item" href="{{ route('productos.registrar') }}">Registrar producto</a></li>
    <li><a class="dropdown-item" href="{{ route('productos.mostrar') }}">Mostrar productos</a></li>
  </ul>
</div>
            </div>
            
            <!-- MENÚ DE PERFIL (MANTIENE TODAS TUS VALIDACIONES) -->
           <ul class="navbar-nav">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="perfilDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <!-- CUADRO NEGRO CON "Administrador" -->
            <div class="badge-container me-2">  
                <span class="admin-badge">
               <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
    </svg> 
                    @auth
                        {{ Auth::user()->rol ?? 'Invitado' }}
                    @else
                        Invitado
                    @endauth
                </span>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="perfilDropdown">
            @if(Auth::check())
                <li>
                    <div class="dropdown-item-text">
                        <div class="mb-2">
                            <strong>{{ Auth::user()->nombre }}</strong>
                        </div>
                        <small class="text-muted">{{ Auth::user()->correo }}</small>
                    </div>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
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
<footer class="text-center mt-5 py-3 bg-light">
    <p>&copy; {{ date('Y') }} Universidad Nacional de El Salvador. Todos los derechos reservados.</p>
</footer>
</html>