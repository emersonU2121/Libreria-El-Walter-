@extends('menu')

@section('contenido')
<div class="compact-form">
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
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required autocomplete="off">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-dark">Ingresar</button>
            <a href="{{ route('inicio') }}" class="btn btn-danger">Cancelar</a>
        </div>

        <div class="mt-3 text-center">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @else
                <a href="#" onclick="event.preventDefault(); alert('Recuperación de contraseña aún no disponible.');">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>
    </form>
</div>
@endsection
