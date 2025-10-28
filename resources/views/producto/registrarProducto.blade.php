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
    
    /* Estilos para el contenedor de la cámara */
    .contenedor-camara {
        position: relative;
        width: 100%;
    }
    
    #video {
        width: 100%;
        height: 200px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: #000;
    }
    
    #canvas {
        display: none;
    }
    
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }
</style>

<div class="formulario-rectangular">
    <h1>Registro de Producto</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('productos.store') }}" method="post" autocomplete="off" id="formProducto" enctype="multipart/form-data">
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

                {{-- LECTOR DE CÓDIGOS DE BARRAS --}}
                <div class="campo-formulario">
                    <label class="form-label">Lectura por cámara</label>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" id="btnIniciarCamara" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-camera"></i> Activar cámara
                        </button>
                        <button type="button" id="btnDetenerCamara" class="btn btn-outline-secondary btn-sm" style="display:none;">
                            <i class="fas fa-stop"></i> Detener
                        </button>
                    </div>
                    
                    {{-- Video y canvas --}}
                    <div class="mt-2 contenedor-camara" id="contenedorCamara" style="display:none;">
                        <video id="video" autoplay playsinline muted></video>
                        <canvas id="canvas"></canvas>
                        <div class="mt-1">
                            <small class="text-muted">Enfoca el código de barras con la cámara. Se detendrá automáticamente al detectar un código válido.</small>
                        </div>
                    </div>
                    
                    {{-- Resultado --}}
                    <div id="resultadoCodigo" class="mt-2 alert alert-info" style="display:none;"></div>
                </div>

                {{-- IMAGEN --}}
                <div class="campo-formulario">
                    <label for="imagen" class="form-label">Imagen del producto (opcional)</label>
                    <input type="file" id="imagen" name="imagen" class="form-control"
                           accept="image/jpeg,image/png,image/webp">
                    <small class="text-muted d-block">Formatos: JPG, PNG o WEBP. Máx 2MB.</small>
                    @error('imagen')<div class="text-danger small">{{ $message }}</div>@enderror

                    {{-- Preview --}}
                    <div class="mt-2">
                        <img id="preview_nueva" src="#" alt="Vista previa de la imagen" style="max-height:120px; display:none; border:1px solid #eee; padding:4px; border-radius:6px;">
                    </div>
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
                    <label for="precio" class="form-label">Precio de compra</label>
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
// ===== PREVIEW DE IMAGEN =====
document.addEventListener('DOMContentLoaded', function() {
    const inputImagen = document.getElementById('imagen');
    const previewImagen = document.getElementById('preview_nueva');
    
    if (inputImagen && previewImagen) {
        inputImagen.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validar que sea una imagen
                if (!file.type.match('image.*')) {
                    alert('Por favor selecciona una imagen válida');
                    inputImagen.value = '';
                    previewImagen.style.display = 'none';
                    return;
                }
                
                // Crear URL para la previsualización
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImagen.src = e.target.result;
                    previewImagen.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            } else {
                // Si no hay archivo, ocultar preview
                previewImagen.style.display = 'none';
            }
        });
    }
});

// ===== VALIDACIONES EXISTENTES =====
(() => {
    // ===== ID PRODUCTO: solo dígitos, máx 20, sin ceros a la izquierda =====
    const idp = document.getElementById('idproducto');
    if (idp) {
        idp.addEventListener('input', () => {
            let v = idp.value.replace(/\D/g,'').slice(0,20);
            v = v.replace(/^0+/, ''); // sin ceros iniciales
            idp.value = v;
        });
    }

    // ===== PRECIO & PRECIO_VENTA: coma o punto; máx 2 decimales =====
    const price = document.getElementById('precio');
    const priceVenta = document.getElementById('precio_venta');

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
        if (inp) {
            inp.addEventListener('input', () => {
                inp.value = formatPrice(inp.value);
            });
        }
    });

    // Normalizar antes de enviar: coma -> punto y quitar separador final suelto
    const form = document.getElementById('formProducto');
    if (form) {
        form.addEventListener('submit', () => {
            [price, priceVenta].forEach(el => {
                if (el) {
                    let v = (el.value || '').trim();
                    if (/[.,]$/.test(v)) v = v.slice(0, -1);
                    el.value = v.replace(',', '.');
                }
            });
        });
    }

    // ===== Estado automático + aviso de stock bajo =====
    const stock = document.getElementById('stock');
    const estadoView = document.getElementById('estado_view');
    const hint = document.getElementById('low_stock_hint');
    
    function recompute() {
        const s = parseInt(stock?.value || '0', 10);
        if (estadoView) {
            estadoView.value = s > 0 ? 'disponible' : 'agotado';
        }
        if (hint) {
            if (s > 0 && s <= 5) {
                hint.classList.remove('d-none');
            } else {
                hint.classList.add('d-none');
            }
        }
    }
    
    if (stock) {
        stock.addEventListener('input', recompute);
    }
    document.addEventListener('DOMContentLoaded', recompute);
})();

// ===== VALIDACIÓN PRECIO VENTA =====
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('formProducto');
    if (!form) return;

    const price = document.getElementById('precio');
    const pv = document.getElementById('precio_venta');

    // Crear mensaje de error dinámico si no existe
    let errPv = document.getElementById('err_pv_create');
    if (!errPv && pv) {
        errPv = document.createElement('div');
        errPv.id = 'err_pv_create';
        errPv.className = 'text-danger small d-none';
        errPv.textContent = 'El precio de venta no puede ser menor que el precio unitario.';
        pv.parentElement.appendChild(errPv);
    }

    form.addEventListener('submit', (e) => {
        let vP = (price?.value || '').trim();
        let vPV = (pv?.value || '').trim();

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
            if (pv) pv.classList.add('is-invalid');
            if (errPv) errPv.classList.remove('d-none');
            return;
        } else {
            if (pv) pv.classList.remove('is-invalid');
            if (errPv) errPv.classList.add('d-none');
        }

        // reasigna valores normalizados antes de enviar
        if (price) price.value = vP;
        if (pv) pv.value = vPV;
    });
});
</script>

<!-- LECTOR DE CÓDIGOS DE BARRAS MEJORADO -->
<script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnIniciar = document.getElementById('btnIniciarCamara');
    const btnDetener = document.getElementById('btnDetenerCamara');
    const contenedorCamara = document.getElementById('contenedorCamara');
    const video = document.getElementById('video');
    const resultadoCodigo = document.getElementById('resultadoCodigo');
    const idProductoInput = document.getElementById('idproducto');
    
    let stream = null;
    let scanning = false;
    let codigoDetectado = false;

    // Iniciar cámara
    if (btnIniciar) {
        btnIniciar.addEventListener('click', async function() {
            try {
                // Reiniciar estado
                codigoDetectado = false;
                
                // Obtener el stream de la cámara
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: "user",
                        width: { ideal: 1280 },
                        height: { ideal: 720 }
                    } 
                });
                
                // Mostrar el video
                video.srcObject = stream;
                video.setAttribute('autoplay', '');
                video.setAttribute('muted', '');
                video.setAttribute('playsinline', '');
                
                // Mostrar interfaz
                contenedorCamara.style.display = 'block';
                btnIniciar.style.display = 'none';
                btnDetener.style.display = 'inline-block';
                
                if (resultadoCodigo) {
                    resultadoCodigo.innerHTML = 'Escaneando... Enfoca el código de barras con la cámara de manera legible';
                    resultadoCodigo.className = 'mt-2 alert alert-info';
                    resultadoCodigo.style.display = 'block';
                }
                
                // Iniciar Quagga inmediatamente
                iniciarQuagga();
                
            } catch (error) {
                console.error('Error al acceder a la cámara:', error);
                alert('No se pudo acceder a la cámara. Asegúrate de permitir el acceso.');
            }
        });
    }

    // Detener cámara manualmente
    if (btnDetener) {
        btnDetener.addEventListener('click', function() {
            detenerCamara();
            // 👇 LIMPIAR EL MENSAJE CUANDO SE DETIENE MANUALMENTE
            if (resultadoCodigo) {
                resultadoCodigo.style.display = 'none';
            }
        });
    }

    function detenerCamara() {
        scanning = false;
        codigoDetectado = false;
        
        // Detener Quagga
        if (typeof Quagga !== 'undefined') {
            Quagga.stop();
        }
        
        // Detener stream
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        
        // Ocultar interfaz
        if (contenedorCamara) contenedorCamara.style.display = 'none';
        if (btnIniciar) btnIniciar.style.display = 'inline-block';
        if (btnDetener) btnDetener.style.display = 'none';
        
        // Limpiar video
        if (video) {
            video.srcObject = null;
        }
    }

    function iniciarQuagga() {
        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: video,
                constraints: {
                    facingMode: "user",
                    width: 1280,
                    height: 720
                }
            },
            decoder: {
                readers: [
                    "code_128_reader",
                    "ean_reader", 
                    "ean_8_reader",
                    "code_39_reader",
                    "upc_reader",
                    "upc_e_reader"
                ]
            },
            locator: {
                patchSize: "medium",
                halfSample: true
            },
            locate: true,
            numOfWorkers: navigator.hardwareConcurrency || 2
        }, function(err) {
            if (err) {
                console.error('Error al inicializar Quagga:', err);
                if (resultadoCodigo) {
                    resultadoCodigo.innerHTML = '❌ Error al iniciar el escáner. Intenta recargar la página.';
                    resultadoCodigo.className = 'mt-2 alert alert-danger';
                    resultadoCodigo.style.display = 'block';
                }
                detenerCamara();
                return;
            }
            
            console.log('Quagga inicializado correctamente');
            Quagga.start();
            scanning = true;
        });

        // Detectar cuando se encuentra un código
        Quagga.onDetected(function(result) {
            if (codigoDetectado) return; // Evitar múltiples detecciones
            
            if (result && result.codeResult && result.codeResult.code) {
                const codigoLeido = result.codeResult.code.trim();
                
                console.log('Código detectado:', codigoLeido, 'Tipo:', result.codeResult.format);
                
                // Validar que sea numérico y tenga longitud adecuada
                if (/^\d+$/.test(codigoLeido) && codigoLeido.length >= 8 && codigoLeido.length <= 20) {
                    codigoDetectado = true;
                    
                    // Llenar el campo ID
                    if (idProductoInput) {
                        idProductoInput.value = codigoLeido;
                        idProductoInput.dispatchEvent(new Event('input'));
                    }
                    
                    // Mostrar mensaje de éxito
                    if (resultadoCodigo) {
                        resultadoCodigo.innerHTML = `✅ <strong>Código leído correctamente</strong><br>
                                                    <small>Puedes continuar con el formulario.</small>`;
                        resultadoCodigo.className = 'mt-2 alert alert-success';
                        resultadoCodigo.style.display = 'block';
                    }
                    
                    // DETENER CÁMARA INMEDIATAMENTE
                    detenerCamara();
                    
                } else {
                    if (resultadoCodigo) {
                        resultadoCodigo.innerHTML = `⚠️ Código no válido: ${codigoLeido}<br>
                                                    <small>Debe tener 8-20 dígitos numéricos</small>`;
                        resultadoCodigo.className = 'mt-2 alert alert-warning';
                        resultadoCodigo.style.display = 'block';
                    }
                }
            }
        });
    }

    // Limpiar al cerrar la página
    window.addEventListener('beforeunload', detenerCamara);
});
</script>

@endsection