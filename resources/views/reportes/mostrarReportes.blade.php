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
                    </tbody>
                </table>
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
@endsection