@extends('menu')

@section('contenido')
<div class="compact-form">
    <h1>Registro de Categorías</h1>

    @if(session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categorias.store') }}" method="post" autocomplete="off" novalidate id="formRegistro">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Categoría</label>
            <input type="text" id="nombre" name="nombre" class="form-control"
                   value="{{ old('nombre') }}" required>
            @error('nombre')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-dark">Registrar Categoría</button>
            <a href="{{ route('categorias.mostrarC') }}" class="btn btn-danger">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('formRegistro');
  if (!form) return;

  const nombreInput = document.getElementById('nombre');
  
  // Validación en tiempo real
  nombreInput.addEventListener('input', function() {
    if (nombreInput.value.trim() === '') {
      nombreInput.classList.add('is-invalid');
    } else {
      nombreInput.classList.remove('is-invalid');
    }
  });

  // Validación al enviar
  form.addEventListener('submit', function(e) {
    if (nombreInput.value.trim() === '') {
      e.preventDefault();
      nombreInput.classList.add('is-invalid');
      nombreInput.focus();
    }
  });
});
</script>
@endsection
