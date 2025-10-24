@extends('menu')

@section('contenido')
 <link href="{{ asset('css/inicio.css') }}" rel="stylesheet">

<div class="page-bg">
  <img src="{{ asset('images/papeleria-hero.png') }}" alt="Fondo Librería El Walter">
</div>

<section class="main-hero">
  <div>
    <h1>Bienvenido a Librería "El Walter"</h1>
    <p>Cojutepeque, Cuscatlán Sur</p>
  </div>

  <div class="cards-container">
    <!-- Card de Stock Normal -->
    <div class="card stock-normal" style="animation-delay: 0.1s">
      <div class="title">
        <span style="background: linear-gradient(135deg, #10b981, #059669);">
          <svg width="20" fill="currentColor" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
            <path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"></path>
          </svg>
        </span>
        <p class="title-text">Stock Normal</p>
        <p class="percent" style="color: #059669;">
          {{ $porcentajeStockNormal }}%
        </p>
      </div>
      <div class="data">
        <p>{{ $productoStockNormalCount }}</p>
        <p style="font-size: 1rem; margin: 0; color: #6b7280;">Productos con stock ≥ 5</p>
        <div class="range">
          <div class="fill" style="background: linear-gradient(90deg, #10b981, #059669); --fill-width: {{ $porcentajeStockNormal }}%;"></div>
        </div>
      </div>
    </div>

    <!-- Card de Stock Bajo -->
    <div class="card stock-bajo" style="animation-delay: 0.3s">
      <div class="title">
        <span style="background: linear-gradient(135deg, #f59e0b, #d97706);">
          <svg width="20" fill="currentColor" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
            <path d="M1024 1375v-190q0-14-9.5-23.5t-22.5-9.5h-192q-13 0-22.5 9.5t-9.5 23.5v190q0 14 9.5 23.5t22.5 9.5h192q13 0 22.5-9.5t9.5-23.5zm-2-374l18-459q0-12-10-19-13-11-24-11h-220q-11 0-24 11-10 7-10 21l17 457q0 10 10 16.5t24 6.5h185q14 0 23.5-6.5t10.5-16.5zm-14-934l768 1408q35 63-2 126-17 29-46.5 46t-63.5 17h-1536q-34 0-63.5-17t-46.5-46q-37-63-2-126l768-1408q17-31 47-49t65-18 65 18 47 49z"></path>
          </svg>
        </span>
        <p class="title-text">Stock Bajo</p>
        <p class="percent" style="color: #d97706;">
          {{ $porcentajeStockBajo }}%
        </p>
      </div>
      <div class="data">
        <p>{{ $productosStockBajo->count() }}</p>
        <p style="font-size: 1rem; margin: 0; color: #6b7280;">Productos con stock ≤ 5</p>
        
        @if($productosStockBajo->count() > 0)
        <div class="productos-lista">
          @foreach($productosStockBajo as $producto)
          <div class="producto-item">
            <span class="producto-nombre">{{ Str::limit($producto->nombre, 20) }}</span>
            <span class="producto-stock">{{ $producto->stock }} unidades</span>
          </div>
          @endforeach
        </div>
        @else
        <p style="font-size: 0.9rem; color: #9ca3af; text-align: center; margin-top: 1rem;">
          No hay productos con stock bajo
        </p>
        @endif
      </div>
    </div>

    <!-- Card de Stock Agotado -->
    <div class="card stock-agotado" style="animation-delay: 0.5s">
      <div class="title">
        <span style="background: linear-gradient(135deg, #ef4444, #dc2626);">
          <svg width="20" fill="currentColor" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
            <path d="M1024 1375v-190q0-14-9.5-23.5t-22.5-9.5h-192q-13 0-22.5 9.5t-9.5 23.5v190q0 14 9.5 23.5t22.5 9.5h192q13 0 22.5-9.5t9.5-23.5zm-2-374l18-459q0-12-10-19-13-11-24-11h-220q-11 0-24 11-10 7-10 21l17 457q0 10 10 16.5t24 6.5h185q14 0 23.5-6.5t10.5-16.5zm-14-934l768 1408q35 63-2 126-17 29-46.5 46t-63.5 17h-1536q-34 0-63.5-17t-46.5-46q-37-63-2-126l768-1408q17-31 47-49t65-18 65 18 47 49z"></path>
          </svg>
        </span>
        <p class="title-text">Stock Agotado</p>
        <p class="percent" style="color: #dc2626;">
          {{ $productosAgotados->count() }}
        </p>
      </div>
      <div class="data">
        <p>{{ $productosAgotados->count() }}</p>
        <p style="font-size: 1rem; margin: 0; color: #6b7280;">Productos sin existencias</p>
        
        @if($productosAgotados->count() > 0)
        <div class="productos-lista">
          @foreach($productosAgotados as $producto)
          <div class="producto-item">
            <span class="producto-nombre">{{ Str::limit($producto->nombre, 20) }}</span>
            <span class="producto-stock">Agotado</span>
          </div>
          @endforeach
        </div>
        @else
        <p style="font-size: 0.9rem; color: #9ca3af; text-align: center; margin-top: 1rem;">
          No hay productos agotados
        </p>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection