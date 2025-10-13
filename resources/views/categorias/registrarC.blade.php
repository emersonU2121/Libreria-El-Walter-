@extends('menu')
@section('title','Registrar categoría')

@section('contenido')
<div class="card">
  <div class="card-header"><h5 class="mb-0">Nueva categoría</h5></div>
  <div class="card-body">
    <form action="{{ route('categorias.store') }}" method="POST" autocomplete="off">
      @csrf
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        @error('nombre')<div class="text-danger small">{{ $message }}</div>@enderror
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary">Guardar</button>
        <a href="{{ route('categorias.mostrarC') }}" class="btn btn-link">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
