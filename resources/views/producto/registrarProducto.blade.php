@extends('menu')

@section('contenido')
<style>
     .formulario-rectangular {
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin: 80px auto 60px auto;
        padding: 30px;
        min-height: 500px;
    }
    
    .campo-formulario {
        margin-bottom: 1.5rem;
        min-height: 90px; 
    }
    
    .campo-formulario .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .btn-container {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }
    
    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
    }
    
    /* Asegurar que los campos tengan la misma altura */
    .columna-campos {
        display: flex;
        flex-direction: column;
    }
    
    .text-muted {
        font-size: 0.8rem;
    }
    
    /* Alinear verticalmente los campos opuestos */
    .row-campos {
        align-items: start;
    }
</style>

<div class="formulario-rectangular">
    <h1>Registro de Producto</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('productos.store') }}" method="post" autocomplete="off" id="formProducto">
        @csrf

        <div class="row">
            <!-- COLUMNA IZQUIERDA - 4 CAMPOS -->
            <div class="col-md-6">
                {{-- ID PRODUCTO --}}
                <div class="campo-formulario">
                    <label for="idproducto" class="form-label">Identificador del producto</label>
                    <input type="text" id="idproducto" name="idproducto" class="form-control"
                           inputmode="numeric" pattern="\d{8,13}" maxlength="13" minlength="8"
                           value="{{ old('idproducto') }}" required>
                    <small class="text-muted">Solo números, máximo 13 y mínimo 8.</small>
                    @error('idproducto')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                {{-- NOMBRE --}}
                <div class="campo-formulario">
                    <label for="nombre" class="form-label">Nombre del producto</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                           value="{{ old('nombre') }}" required>
                    @error('nombre')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                {{-- PRECIO UNITARIO --}}
                <div class="campo-formulario">
                    <label for="precio" class="form-label">Precio unitario</label>
                    <input type="text" inputmode="decimal" id="precio" name="precio"
                           class="form-control" placeholder="0,00 o 0.00"
                           value="{{ old('precio') }}" required>
                    <small class="text-muted">Solo números y hasta 2 decimales. Ej: 2,50 o 2.50</small>
                    @error('precio')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                {{-- PRECIO VENTA --}}
                <div class="campo-formulario">
                    <label for="precio_venta" class="form-label">Precio de venta</label>
                    <input type="text" inputmode="decimal" id="precio_venta" name="precio_venta"
                           class="form-control" placeholder="0,00 o 0.00"
                           value="{{ old('precio_venta') }}" required>
                    <small class="text-muted">Solo números y hasta 2 decimales. Ej: 2,50 o 2.50</small>
                    @error('precio_venta')<div class="text-danger small">{{ $message }}</div>@enderror
                    <div id="err_pv_create" class="text-danger small d-none">El precio de venta no puede ser menor que el precio unitario.</div>
                </div>
            </div>

            <!-- COLUMNA DERECHA - 4 CAMPOS -->
            <div class="col-md-6">
                {{-- EXISTENCIAS --}}
                <div class="campo-formulario">
                    <label for="stock" class="form-label">Existencias</label>
                    <input type="number" min="0" id="stock" name="stock" class="form-control"
                           value="{{ old('stock') }}" required>
                    <small id="low_stock_hint" class="text-warning d-none">Stock bajo (≤ 5).</small>
                    @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                {{-- ESTADO --}}
                <div class="campo-formulario">
                    <label class="form-label">Estado</label>
                    <input type="text" id="estado_view" class="form-control" value="disponible" readonly>
                </div>

                {{-- MARCA --}}
                <div class="campo-formulario">
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
                    <select class="form-select" disabled>
                        <option>No hay marcas registradas</option>
                    </select>
                    @endif
                    @error('idmarca')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>

                {{-- CATEGORÍA --}}
                <div class="campo-formulario">
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
                    <select class="form-select" disabled>
                        <option>No hay categorías registradas</option>
                    </select>
                    @endif
                    @error('idcategoria')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- BOTONES CENTRADOS EN LA PARTE INFERIOR -->
        <div class="btn-container">
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

    // ===== PRECIO & PRECIO_VENTA: coma o punto; máx 2 decimales =====
    const price       = document.getElementById('precio');
    const priceVenta  = document.getElementById('precio_venta');

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

    [price, priceVenta].forEach(inp => {
        inp.addEventListener('input', () => {
            inp.value = formatPrice(inp.value);
        });
    });

    // Normalizar antes de enviar: coma -> punto y quitar separador final suelto
    const form = document.getElementById('formProducto');
    form.addEventListener('submit', () => {
        [price, priceVenta].forEach(el => {
            let v = (el.value || '').trim();
            if (/[.,]$/.test(v)) v = v.slice(0, -1);
            el.value = v.replace(',', '.');
        });
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

<script>
// === Validar que el precio de venta no sea menor que el precio (Registrar) ===
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formProducto');
    if (!form) return;

    const price = document.getElementById('precio');
    const pv = document.getElementById('precio_venta');

    // Crear mensaje de error dinámico si no existe
    let errPv = document.getElementById('err_pv_create');
    if (!errPv) {
        errPv = document.createElement('div');
        errPv.id = 'err_pv_create';
        errPv.className = 'text-danger small d-none';
        errPv.textContent = 'El precio de venta no puede ser menor que el precio.';
        pv.parentElement.appendChild(errPv);
    }

    form.addEventListener('submit', (e) => {
        let vP = (price.value || '').trim();
        let vPV = (pv.value || '').trim();

        // Normaliza los valores (coma -> punto)
        if (/[.,]$/.test(vP)) vP = vP.slice(0, -1);
        if (/[.,]$/.test(vPV)) vPV = vPV.slice(0, -1);
        vP = vP.replace(',', '.');
        vPV = vPV.replace(',', '.');

        const nP = parseFloat(vP) || 0;
        const nPV = parseFloat(vPV) || 0;

        // Validar
        if (nPV < nP) {
            e.preventDefault();
            pv.classList.add('is-invalid');
            errPv.classList.remove('d-none');
            return;
        } else {
            pv.classList.remove('is-invalid');
            errPv.classList.add('d-none');
        }

        // reasigna valores normalizados antes de enviar
        price.value = vP;
        pv.value = vPV;
    });
});
</script>

@endsection