<!-- resources/views/reportes/mostrarReportes.blade.php -->
@extends('menu')

@section('contenido')
<div class="container-fluid py-4 mt-5 px-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-dark">Reportes del Sistema</h2>
        <a href="{{ route('inicio') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    {{-- Alertas --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Resumen de datos --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
                <div class="card-body text-center">
                    <h4>{{ $categorias }}</h4>
                    <p>Categorías</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
                <div class="card-body text-center">
                    <h4>{{ $marcas }}</h4>
                    <p>Marcas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
                <div class="card-body text-center">
                    <h4>{{ $productos }}</h4>
                    <p>Productos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
                <div class="card-body text-center">
                    <h4>{{ $usuarios }}</h4>
                    <p>Usuarios</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de reportes disponibles --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 text-dark">Reportes Disponibles</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Reporte</th>
                            <th>Descripción</th>
                            <th>Registros</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <i class="fas fa-folder me-2" style="color: #2c3e50;"></i>
                                <strong>Reporte de Categorías</strong>
                            </td>
                            <td>Lista completa de todas las categorías registradas</td>
                            <td>{{ $categorias }} registros</td>
                            <td class="text-center">
                                <a href="{{ route('reportes.categorias') }}" class="btn btn-sm text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
                                    <i class="fas fa-download me-1"></i>Descargar PDF
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-tag me-2" style="color: #2c3e50;"></i>
                                <strong>Reporte de Marcas</strong>
                            </td>
                            <td>Lista completa de todas las marcas registradas</td>
                            <td>{{ $marcas }} registros</td>
                            <td class="text-center">
                                <a href="{{ route('reportes.marcas') }}" class="btn btn-sm text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
                                    <i class="fas fa-download me-1"></i>Descargar PDF
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-box me-2" style="color: #2c3e50;"></i>
                                <strong>Reporte de Productos</strong>
                            </td>
                            <td>Inventario completo de productos con precios y stock</td>
                            <td>{{ $productos }} registros</td>
                            <td class="text-center">
                                <a href="{{ route('reportes.productos') }}" class="btn btn-sm text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
                                    <i class="fas fa-download me-1"></i>Descargar PDF
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-users me-2" style="color: #2c3e50;"></i>
                                <strong>Reporte de Usuarios</strong>
                            </td>
                            <td>Lista de usuarios del sistema con roles y estados</td>
                            <td>{{ $usuarios }} registros</td>
                            <td class="text-center">
                                <a href="{{ route('reportes.usuarios') }}" class="btn btn-sm text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
                                    <i class="fas fa-download me-1"></i>Descargar PDF
                                </a>
                            </td>
                        </tr>
<tr>
    <td>
        <i class="fas fa-chart-line me-2" style="color: #2c3e50;"></i>
        <strong>Reporte de Ventas por Mes</strong>
    </td>
    <td>Resumen completo de ventas del mes seleccionado con totales e ingresos</td>
    <td>Mes seleccionado</td>
    <td class="text-center">
        <button type="button" class="btn btn-sm text-white" style="background-color: #2c3e50; border-color: #2c3e50;"
                data-bs-toggle="modal" data-bs-target="#modalSeleccionarMes">
            <i class="fas fa-download me-1"></i>Seleccionar Mes
        </button>
    </td>
</tr>
<tr>
    <td>
        <i class="fas fa-trophy me-2" style="color: #2c3e50;"></i>
        <strong>Artículos Más Vendidos</strong>
    </td>
    <td>Top de productos más vendidos y estadísticas (últimos 30 días)</td>
    <td>Últimos 30 días</td>
    <td class="text-center">
        <a href="{{ route('reportes.articulos-mas-vendidos') }}" class="btn btn-sm text-white" style="background-color: #2c3e50; border-color: #2c3e50;">
            <i class="fas fa-download me-1"></i>Descargar PDF
        </a>
    </td>
</tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ✅ AGREGAR ESTE MODAL NUEVO: -->
<!-- Modal para seleccionar mes -->
<div class="modal fade" id="modalSeleccionarMes" tabindex="-1" aria-labelledby="modalSeleccionarMesLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSeleccionarMesLabel">Seleccionar Mes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSeleccionarMes" action="{{ route('reportes.ventas-mes') }}" method="GET">
                    <div class="mb-3">
                        <label for="mesSelect" class="form-label">Mes</label>
                        <select name="mes" id="mesSelect" class="form-select" required>
                            <option value="">Seleccionar mes</option>
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                    <div class="mb-3">
    <label for="añoSelect" class="form-label">Año</label>
    <select name="año" id="añoSelect" class="form-select" required>
        <option value="">Seleccionar año</option>
        @php
            // ✅ SISTEMA 100% AUTOMÁTICO: Si $rangoAños no existe, se genera aquí mismo
            $añosDisponibles = $rangoAños ?? range(now()->year, now()->year - 9);
        @endphp
        @foreach($añosDisponibles as $año)
            <option value="{{ $año }}" {{ $año == now()->year ? 'selected' : '' }}>
                {{ $año }}
            </option>
        @endforeach
    </select>
</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn text-white" style="background-color: #2c3e50; border-color: #2c3e50;"
                        onclick="document.getElementById('formSeleccionarMes').submit()">
                    <i class="fas fa-download me-1"></i>Generar PDF
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
}
.table th {
    border-top: none;
    font-weight: 600;
}
.btn:hover {
    background-color: 0 2px 5px rgba(0,0,0,0.1);
    border-color: 0 2px 5px rgba(0,0,0,0.1);
}
</style>

<!-- ✅ AGREGAR ESTE SCRIPT: -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preseleccionar mes y año actual en el modal
    const mesActual = new Date().getMonth() + 1; // Enero es 0, sumamos 1
    const añoActual = new Date().getFullYear();
    
    const modal = document.getElementById('modalSeleccionarMes');
    if (modal) {
        modal.addEventListener('show.bs.modal', function() {
            document.getElementById('mesSelect').value = mesActual;
            document.getElementById('añoSelect').value = añoActual;
        });
    }
});
</script>
@endsection