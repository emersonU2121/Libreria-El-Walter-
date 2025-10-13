@extends('menu')

@section('contenido')
<div class="compact-form">
  <h1>Registro de Producto</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form action="{{ route('productos.store') }}" method="post" autocomplete="off" id="formProducto">
    @csrf

    {{-- ID PRODUCTO (único) --}}
    <div class="mb-3">
      <label for="idproducto" class="form-label">Identificador del producto</label>
      <input
        type="text"
        id="idproducto"
        name="idproducto"
        class="form-control"
        inputmode="numeric"
        pattern="\d{1,20}"
        maxlength="20"
        value="{{ old('idproducto') }}"
        required
      >
      <small class="text-muted">Solo numeros, máximo 20 (no iniciar en 0).</small>
      @error('idproducto')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    {{-- NOMBRE --}}
    <div class="mb-3">
      <label for="nombre" class="form-label">Nombre del producto</label>
      <input type="text" id="nombre" name="nombre" class="form-control"
             value="{{ old('nombre') }}" required>
      @error('nombre')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    {{-- PRECIO --}}
    <div class="mb-3">
      <label for="precio" class="form-label">Precio unitario</label>
      <input
        type="text"
        inputmode="decimal"
        id="precio"
        name="precio"
        class="form-control"
        placeholder="0,00 o 0.00"
        value="{{ old('precio') }}"
        required
      >
      <small class="text-muted">Solo números y hasta 2 decimales. Ej: 2,50 o 2.50</small>
      @error('precio')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    {{-- STOCK --}}
    <div class="mb-3">
      <label for="stock" class="form-label">Existencias</label>
      <input type="number" min="0" id="stock" name="stock" class="form-control"
             value="{{ old('stock') }}" required>
      <small id="low_stock_hint" class="text-warning d-none">Stock bajo (≤ 5).</small>
      @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    {{-- ESTADO (solo lectura, calculado por stock) --}}
    <div class="mb-3">
      <label class="form-label">Estado</label>
      <input type="text" id="estado_view" class="form-control" value="disponible" readonly>
    </div>

    {{-- MARCA --}}
    <div class="mb-3">
      <label for="idmarca" class="form-label">Marca</label>
      @if(isset($marcas) && $marcas->count())
        <select id="idmarca" name="idmarca" class="form-select" required>
          <option value="" disabled selected>— Selecciona una marca —</option>
          @foreach($marcas as $m)
            <option value="{{ $m->idmarca }}" {{ old('idmarca')==$m->idmarca ? 'selected':'' }}>
              {{ $m->nombre }}
            </option>
          @endforeach
        </select>
      @else
        <select class="form-select" disabled><option>No hay marcas registradas</option></select>
      @endif
      @error('idmarca')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    {{-- CATEGORÍA --}}
    <div class="mb-3">
      <label for="idcategoria" class="form-label">Categoría</label>
      @if(isset($categorias) && $categorias->count())
        <select id="idcategoria" name="idcategoria" class="form-select" required>
          <option value="" disabled selected>— Selecciona una categoría —</option>
          @foreach($categorias as $c)
            <option value="{{ $c->idcategoria }}" {{ old('idcategoria')==$c->idcategoria ? 'selected':'' }}>
              {{ $c->nombre }}
            </option>
          @endforeach
        </select>
      @else
        <select class="form-select" disabled><option>No hay categorías registradas</option></select>
      @endif
      @error('idcategoria')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-dark">Guardar</button>
      <a href="{{ route('inicio') }}" class="btn btn-danger">Cancelar</a>
    </div>
  </form>
</div>

<script>
(() => {
  // ===== ID PRODUCTO: solo dígitos, máx 20, sin ceros a la izquierda =====
  const idp = document.getElementById('idproducto');
  idp.addEventListener('input', () => {
    let v = idp.value.replace(/\D/g,'').slice(0,20);
    v = v.replace(/^0+/, ''); // sin ceros iniciales
    idp.value = v;
  });

  // ===== PRECIO: permite coma o punto; máx 2 decimales =====
  const price = document.getElementById('precio');

  function formatPrice(raw) {
    let s = (raw || '').toString().replace(/[^0-9.,]/g, '');

    const sepIndex = s.search(/[.,]/);
    if (sepIndex === -1) {
      return s.replace(/[.,]/g,'').replace(/^0+(?=\d)/,'');
    }

    const sep = s[sepIndex]; // ',' o '.'
    const ent = s.slice(0, sepIndex).replace(/[.,]/g,'').replace(/^0+(?=\d)/,'');
    const dec = s.slice(sepIndex + 1).replace(/[.,]/g,'').slice(0, 2);
    const entFinal = ent.length ? ent : '0';

    return dec.length ? (entFinal + sep + dec) : (entFinal + sep);
  }

  price.addEventListener('input', () => {
    price.value = formatPrice(price.value);
  });

  // Normalizar antes de enviar: coma -> punto y quitar separador final suelto
  const form = document.getElementById('formProducto');
  form.addEventListener('submit', () => {
    let v = (price.value || '').trim();
    if (/[.,]$/.test(v)) v = v.slice(0, -1);
    price.value = v.replace(',', '.');
  });

  // ===== Estado automático + aviso de stock bajo =====
  const stock = document.getElementById('stock');
  const estadoView = document.getElementById('estado_view');
  const hint = document.getElementById('low_stock_hint');
  function recompute() {
    const s = parseInt(stock.value || '0', 10);
    estadoView.value = s > 0 ? 'disponible' : 'agotado';
    if (s > 0 && s <= 5) hint.classList.remove('d-none'); else hint.classList.add('d-none');
  }
  stock.addEventListener('input', recompute);
  document.addEventListener('DOMContentLoaded', recompute);
})();
</script>
@endsection