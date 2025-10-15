@extends('menu')

@section('contenido')
<style>
  html, body { margin:0; padding:0; height:100%; }
  .page-bg{ position:fixed; inset:0; z-index:-1; }
  .page-bg img{ width:100%; height:100%; object-fit:cover; object-position:center; filter:brightness(.4); }

  .main-hero{
   /* height:100vh; */
   min-height: 100vh;
    display:flex;
    align-items:top;
    justify-content:center;
    text-align:center;
    color:#fff;
    padding:2rem;
    flex-direction:column;
  }

  /* Ajuste del tamaño de letras */
  .main-hero h1 {
    font-weight: 700;
    font-size: clamp(3rem, 6vw, 5rem);
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
  }

  .main-hero p {
    font-size: clamp(1.2rem, 2.5vw, 2rem);
    font-weight: 500;
    margin-top: 0;
  }

  .cards-container {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 4rem;
  }

  /* Footer fijo */
  footer, .footer, #footer, .site-footer {
    position: relative !important;
    left: 0; 
    right: 0; 
    bottom: 0;
    z-index: 1000;
    backdrop-filter: blur(3.5px);
    -webkit-backdrop-filter: blur(3.5px);
    color: #000;
    padding: 0.8rem 1rem;
    text-align: center;
    margin: 0;
  }

  /* Card Styles */
  .card {
    padding: 1.5rem;
    background: linear-gradient(180deg, #ffffff, #f9f9f9);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    max-width: 340px;
    border-radius: 28px;
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Helvetica Neue", Arial, sans-serif;
    transform: translateY(20px);
    opacity: 0;
    animation: cardFadeUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards;
  }

  .card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
    transition: transform 0.45s ease, box-shadow 0.45s ease;
  }

  .card.stock-bajo {
    border-left: 4px solid #f59e0b;
  }

  .card.stock-agotado {
    border-left: 4px solid #ef4444;
  }

  .card.stock-normal {
    border-left: 4px solid #10b981;
  }

  .title {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
  }

  .title span {
    position: relative;
    padding: 0.6rem;
    width: 1.6rem;
    height: 1.6rem;
    border-radius: 50%;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.35);
    animation: pulse 2.4s ease-in-out infinite;
  }

  .title-text {
    margin-left: 0.75rem;
    color: #1c1c1e;
    font-size: 19px;
    font-weight: 600;
    letter-spacing: -0.02em;
  }

  .percent {
    margin-left: 0.5rem;
    font-weight: 600;
    display: flex;
    font-size: 15px;
  }

  .data {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
  }

  .data p {
    margin-top: 1.25rem;
    margin-bottom: 1.25rem;
    color: #111827;
    font-size: 2.4rem;
    line-height: 2.7rem;
    font-weight: 700;
    text-align: left;
    letter-spacing: -0.03em;
    opacity: 0;
    animation: fadeIn 0.8s ease forwards 0.3s;
  }

  .data .range {
    position: relative;
    background-color: #e5e5ea;
    width: 100%;
    height: 0.55rem;
    border-radius: 9999px;
    overflow: hidden;
  }

  .data .range .fill {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    border-radius: inherit;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15);
    animation: fillBar 1.6s ease forwards 0.5s;
  }

  .productos-lista {
    margin-top: 1rem;
    max-height: 150px;
    overflow-y: auto;
  }

  .producto-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .producto-nombre {
    font-size: 0.9rem;
    color: #374151;
  }

  .producto-stock {
    font-size: 0.8rem;
    font-weight: 600;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
  }

  .stock-bajo .producto-stock {
    background-color: #fef3c7;
    color: #d97706;
  }

  .stock-agotado .producto-stock {
    background-color: #fee2e2;
    color: #dc2626;
  }

  /* Animations */
  @keyframes cardFadeUp {
    from {
      transform: translateY(20px);
      opacity: 0;
    }
    to {
      transform: translateY(0);
      opacity: 1;
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(6px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fillBar {
    from {
      width: 0%;
    }
    to {
      width: var(--fill-width, 76%);
    }
  }

  @keyframes pulse {
    0%, 100% {
      transform: scale(1);
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.35);
    }
    50% {
      transform: scale(1.08);
      box-shadow: 0 6px 14px rgba(0, 0, 0, 0.45);
    }
  }
</style>

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
          {{ $productosStockBajo->count() }}
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