@extends('menu')

@section('contenido')
<style>
  html, body { margin:0; padding:0; height:100%; overflow:hidden; }
  .page-bg{ position:fixed; inset:0; z-index:-1; }
  .page-bg img{ width:100%; height:100%; object-fit:cover; object-position:center; filter:brightness(.6); }

  .main-hero{
    height:100vh;
    display:flex;
    align-items:top;
    justify-content:top;
    text-align:center;
    color:#fff;
    padding:2rem;
    flex-direction:column;
  }

  /* 🔥 Ajuste del tamaño de letras */
  .main-hero h1 {
    font-weight: 700;
    font-size: clamp(3rem, 6vw, 5rem); /* crece en pantallas grandes */
    letter-spacing: 1px;
    margin-bottom: 0.5rem;
  }

  .main-hero p {
    font-size: clamp(1.2rem, 2.5vw, 2rem); /* subtítulo más visible */
    font-weight: 500;
    margin-top: 0;
  }

  /* Footer fijo (de tu layout o global) */
  footer, .footer, #footer, .site-footer {
    position: fixed !important;
    left: 0; right: 0; bottom: 0;
    z-index: 5;
    background: rgba(255,255,255,0.92);
    color: #000;
    padding: 0.8rem 1rem;
    text-align: center;
    backdrop-filter: blur(4px);
  }
</style>

<div class="page-bg">
  <img src="{{ asset('images/papeleria-hero.png') }}" alt="Fondo Librería El Walter">
</div>

<section class="main-hero">
  <div>
    <h1>Bienvenido a Librería “El Walter”</h1>
    <p>Cojutepeque, Cuscatlán Sur</p>
  </div>
</section>
@endsection
