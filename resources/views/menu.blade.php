<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería El Walter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/menu.css') }}" rel="stylesheet">
    @stack('styles')

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-transparent fixed-top px-3">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('inicio') }}">
                <img src="{{ asset('images/W.png') }}" alt="Logo" width="30" height="30" class="me-2">
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

                    {{-- === PRODUCTOS (dropdown) === --}}
                    <div class="dropdown d-inline-block">
                        <a class="value text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="normal" viewBox="0 0 24 24" style="width:20px;height:20px;vertical-align:middle;">
                                <path d="M3 7l9-5 9 5-9 5-9-5zm0 3l9 5 9-5v8l-9 5-9-5V10zm9 8l6-3.33V12l-6 3.33L6 12v2.67L12 18z" fill="currentColor" />
                            </svg>
                            Productos
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('productos.registrar') }}">Registrar producto</a></li>
                            <li><a class="dropdown-item" href="{{ route('productos.mostrar') }}">Mostrar productos</a></li>
                        </ul>
                    </div>

                    <a href="#" class="value text-decoration-none">
                        <svg class="normal" viewBox="0 0 24 24">
                            <path d="M20 6h-3V4c0-1.1-.9-2-2-2h-6c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zM9 4h6v2H9V4zm1 12-3-3 1.41-1.41L10 12.17l5.59-5.59L17 8l-7 8z" fill="currentColor"/>
                        </svg>
                        Inventario
                    </a>

                    {{-- === Marca (dropdown) === --}}
                    <div class="dropdown d-inline-block">
                        <a class="value text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="normal" viewBox="0 0 24 24" style="width:20px;height:20px;vertical-align:middle;">
                                <path d="M23 12l-2.26 1.91.34 2.93-2.84 1.23-1.64 2.57-2.93-.34L12 23l-2.67-2.7-2.93.34-1.64-2.57-2.84-1.23.34-2.93L1 12l2.26-1.91-.34-2.93 2.84-1.23L7.4 3.36l2.93.34L12 1l2.67 2.7 2.93-.34 1.64 2.57 2.84 1.23-.34 2.93ZM11 15l7-7-1.41-1.41L11 12.17 8.41 9.59 7 11l4 4z" fill="currentColor"/>
                            </svg>
                            Marcas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('marcas.mostrar') }}">Mostrar Marca</a></li>
                        </ul>
                    </div>

                    {{-- CATEGORÍA (Dropdown) --}}
                    <div class="dropdown d-inline-block">
                        <a class="value text-decoration-none dropdown-toggle" href="#" id="dropdownCategorias"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="normal" viewBox="0 0 24 24">
                                <path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z" fill="currentColor" />
                            </svg>
                            Categoría
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownCategorias">
                            
                            <li>
                                <a class="dropdown-item" href="{{ route('categorias.mostrarC') }}">
                                    Mostrar Categorías
                                </a>
                            </li>
                        </ul>
                    </div>

                   <a class="value text-decoration-none" href="{{ route('compras.registrar') }}">
    <svg class="normal" viewBox="0 0 24 24">
        <path d="M17 18c-1.11 0-2 .89-2 2a2 2 0 002 2 2 2 0 000-4zM7 18c-1.11 0-2 .89-2 2a2 2 0 002 2 2 2 0 000-4zM7.17 14.75L7.2 14.63 8.1 13h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4h-.01l-1.1 2-2.76 5H8.53l-.13-.27L6.16 6l-.95-2-.94-2H1v2h2l3.6 7.59-1.35 2.44c-.73 1.34.23 2.97 1.75 2.97h12v-2H7.42c-.13 0-.25-.11-.25-.25z" fill="currentColor" />
    </svg>
    Compras
</a>

                    <a href="#" class="value text-decoration-none">
                        <svg class="normal" viewBox="0 0 24 24">
                            <path d="M2 17h2v.5H3v1h1v.5H2v1h3v-4H2v1zm1-9h1V4H2v1h1v3zm-1 3h1.8L2 13.1v.9h3v-1H3.2L5 10.9V10H2v1zm5-6v2h14V5H7zm0 14h14v-2H7v2zm0-6h14v-2H7v2z" fill="currentColor" />
                        </svg>
                        Ventas
                    </a>

                    <!-- MENÚ USUARIOS (SOLO PARA ADMINISTRADORES) -->
                    @auth
                    @if(Auth::user()->rol === 'Administrador')
                    <div class="dropdown">
                        <a class="value text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg class="normal" viewBox="0 0 24 24">
                                <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zm4 18v-6h2.5l-2.54-7.63A3.02 3.02 0 0016.95 6h-2.2c-.79 0-1.53.43-1.92 1.13L10.5 16H7v6h13zM4.5 11c-.83 0-1.5-.67-1.5-1.5S3.67 8 4.5 8s1.5.67 1.5 1.5S5.33 11 4.5 11zM7 20v-6H4v6h3z" fill="currentColor" />
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

                    @auth
@if(Auth::user()->rol === 'Administrador')
<div class="dropdown d-inline-block">
    <a class="value text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <svg class="normal" viewBox="0 0 24 24" style="width:20px;height:20px;vertical-align:middle;">
            <path d="M5 4h14a2 2 0 012 2v8a2 2 0 01-2 2h-6l-4 4v-4H5a2 2 0 01-2-2V6a2 2 0 012-2z" fill="currentColor"/>
        </svg>
        Sistema
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('backups.index') }}">Respaldos</a></li>
    </ul>
</div>
@endif
@endauth



                </div>

                <!-- MENÚ DE PERFIL (MANTIENE TODAS TUS VALIDACIONES) -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="perfilDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- CUADRO NEGRO CON "Administrador" -->
                            <div class="badge-container me-2">
                                <span class="admin-badge">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
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
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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
<footer class="text-center mt-5 py-3 footer-transparent">
    <p>&copy; {{ date('Y') }} Universidad Nacional de El Salvador. Todos los derechos reservados.</p>
</footer>

</html>