@extends('menu')

@section('contenido')
<div class="card shadow p-4 w-100">
  <h2 class="mb-4 text-center">Lista de Productos</h2>

  @if(session('warn_low'))  <div class="alert alert-warning">{{ session('warn_low') }}</div> @endif
  @if(session('warn_zero')) <div class="alert alert-danger">{{ session('warn_zero') }}</div> @endif
  @if(session('ok'))       <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if(session('error'))    <div class="alert alert-danger">{{ session('error') }}</div> @endif

  @php
    $umbral = $umbralStock ?? 5;
    $bajos = $productos->filter(fn($pr) => ($pr->stock ?? 0) > 0 && ($pr->stock ?? 0) <= $umbral);
  @endphp
  @if($bajos->count() > 0)
    <div class="alert alert-warning mb-4">
      <strong>Atención:</strong> {{ $bajos->count() }} producto(s) con stock ≤ {{ $umbral }}.
      @foreach($bajos->take(5) as $b)
        <span class="badge bg-warning text-dark ms-1">{{ $b->nombre }} ({{ $b->stock }})</span>
      @endforeach
      @if($bajos->count() > 5) <span class="ms-1">…</span> @endif
    </div>
  @endif

  @if($productos->isEmpty())
    <div class="alert alert-info text-center">No hay productos registrados.</div>
  @else
    <div class="table-responsive">

      <table class="table table-bordered table-striped text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>Nombre</th>
            <th>Precio unitario</th>
            <th>Existencias</th>
            <th>Estado</th>
            <th>Marca</th>
            <th>Categoría</th>
            <th style="width:260px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
        @foreach($productos as $p)
          @php
            $estadoBD = $p->estado ?? 'disponible';   // 'disponible' | 'agotado'
            $stockVal = (int)($p->stock ?? 0);

            // Visual:
            // - stock == 0 -> agotado (amarillo)
            // - estadoBD == 'agotado' y stock > 0 -> inactivo (gris)
            // - estadoBD == 'disponible' -> disponible (verde)
            $estadoVisual = 'disponible';
            if ($stockVal === 0)        $estadoVisual = 'agotado';
            elseif ($estadoBD === 'agotado') $estadoVisual = 'inactivo';

            $esActivo = ($estadoBD === 'disponible'); // solo activos se pueden editar
            $esBajo   = $stockVal > 0 && $stockVal <= $umbral;
          @endphp
          <tr class="{{ $esBajo ? 'table-warning' : '' }}">
            <td>{{ $p->nombre }}</td>
            <td>${{ number_format($p->precio,2) }}</td>
            <td>
              {{ $p->stock }}
              @if($stockVal === 0)
                <span class="badge bg-danger ms-1">Reabastecer</span>
              @elseif($esBajo)
                <span class="badge bg-warning text-dark ms-1">Bajo</span>
              @endif
            </td>
            <td>
              @if($estadoVisual === 'disponible')
                <span class="badge bg-success">disponible</span>
              @elseif($estadoVisual === 'agotado')
                <span class="badge bg-warning text-dark">agotado</span>
              @else
                <span class="badge bg-secondary">inactivo</span>
              @endif
            </td>
            <td>{{ $p->marca_nombre ?? '—' }}</td>
<td>{{ $p->categoria_nombre ?? '—' }}</td>

            <td class="d-flex gap-2 justify-content-center">

              {{-- Editar: deshabilitado si está inactivo --}}
              <button type="button"
                class="btn btn-sm btn-primary btn-open-edit"
                data-bs-toggle="modal"
                data-bs-target="#modalEditarProducto"
                data-idproducto="{{ $p->idproducto }}"
                data-nombre="{{ $p->nombre }}"
                data-precio="{{ $p->precio }}"
                data-stock="{{ $p->stock }}"
                data-estado="{{ $p->estado }}"
                data-idmarca="{{ $p->idmarca }}"
                data-idcategoria="{{ $p->idcategoria }}"
                data-update-url="{{ route('productos.update', $p->idproducto) }}"
                {{ $esActivo ? '' : 'disabled' }}
                title="{{ $esActivo ? 'Editar' : 'Producto inactivo: reactívalo para editar' }}"
              >Editar</button>

              {{-- Dar de baja / Reactivar --}}
              <button type="button"
                class="btn btn-sm {{ $esActivo ? 'btn-warning' : 'btn-success' }} btn-open-baja"
                data-bs-toggle="modal"
                data-bs-target="#modalBajaProducto"
                data-idproducto="{{ $p->idproducto }}"
                data-nombre="{{ $p->nombre }}"
                data-estado-bd="{{ $estadoBD }}"
                data-activo="{{ $esActivo ? 1 : 0 }}"
                data-inactivar-url="{{ route('productos.inactivo', $p->idproducto) }}"
                data-activar-url="{{ route('productos.activo', $p->idproducto) }}"
              >{{ $esActivo ? 'Dar de baja' : 'Reactivar' }}</button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>

@include('producto._modal_editar_producto')
@include('producto._modal_baja_producto')

<script>
document.addEventListener('DOMContentLoaded', () => {
  // EDITAR: precarga datos y fija action
  document.querySelectorAll('.btn-open-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      const f = document.getElementById('formEditarProducto');
      if (f) f.action = btn.dataset.updateUrl || '#';

      ['idproducto','nombre','precio','stock','idmarca','idcategoria'].forEach(k => {
        const el = document.getElementById('edit_'+k);
        if (el) el.value = btn.dataset[k] ?? '';
      });

      // Estado visual en modal según stock
      const s = document.getElementById('edit_stock');
      const estView = document.getElementById('edit_estado_view');
      const hint = document.getElementById('edit_low_hint');
      const n = parseInt(s.value || '0', 10);
      estView.value = (n > 0) ? 'disponible' : 'agotado';
      if (n > 0 && n <= 5) hint.classList.remove('d-none'); else hint.classList.add('d-none');
    });
  });

  // BAJA / REACTIVAR: fija action y textos
  document.querySelectorAll('.btn-open-baja').forEach(btn => {
    btn.addEventListener('click', () => {
      const esActivo = btn.dataset.activo === '1';
      const f    = document.getElementById('formBajaProducto');
      const msg  = document.getElementById('baja_message_prod');
      const hid  = document.getElementById('baja_idproducto');
      const sBtn = document.getElementById('baja_submit_btn_prod');

      if (hid) hid.value = btn.dataset.idproducto;
      if (msg) {
        msg.innerHTML = esActivo
          ? `¿Estás seguro de dar de baja el producto <strong>${btn.dataset.nombre}</strong>?`
          : `¿Deseas reactivar el producto <strong>${btn.dataset.nombre}</strong>?`;
      }
      if (f) f.action = esActivo ? (btn.dataset.inactivarUrl || '#') : (btn.dataset.activarUrl || '#');

      if (sBtn) {
        sBtn.textContent = esActivo ? 'Sí, dar de baja' : 'Reactivar';
        sBtn.classList.remove('btn-success','btn-warning');
        sBtn.classList.add(esActivo ? 'btn-warning' : 'btn-success');
      }
    });
  });

  // Salvaguarda: evita submit sin action
  ['formEditarProducto','formBajaProducto'].forEach(id => {
    const f = document.getElementById(id);
    if (!f) return;
    f.addEventListener('submit', e => {
      if (!f.action || f.action.endsWith('#')) {
        e.preventDefault();
        alert('No se pudo determinar el destino del formulario.');
      }
    });
  });
});

</script>
@endsection