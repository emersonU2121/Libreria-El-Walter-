<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\marca;

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\inicioController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CompraReporteController;
use App\Http\Controllers\ReporteProductoController;




// ====== Principal ======
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('inicio');
    }
    return view('auth.login');
})->name('login');

// RUTA CORREGIDA - usa el controlador
Route::get('/inicio', [inicioController::class, 'index'])->name('inicio');

// ====== USUARIOS ======

// Formulario de registro (GET)
Route::get('/usuarios/registrar', [UsuarioController::class, 'create'])
     ->name('usuarios.registrar');

// Guardar usuario (POST)
Route::post('/usuarios', [UsuarioController::class, 'store'])
     ->name('usuarios.store');

// Editar usuario (PUT)  <-- usado por el modal Editar
Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');


// Dar de baja (PUT)     <-- usado por el modal Confirmación (cuando está activo)
Route::put('/usuarios/{id}/inactivar', [UsuarioController::class, 'inactivo'])
     ->name('usuarios.inactivo');

// Reactivar (PUT)       <-- usado por el modal Confirmación (cuando está inactivo)
Route::put('/usuarios/{id}/activar', [UsuarioController::class, 'activo'])
     ->name('usuarios.activo');

// Mostrar usuarios (tabla)
Route::get('/usuarios/mostrar', function () {
    $usuarios = Usuario::all();
    return view('usuarios.mostrar', compact('usuarios'));
})->name('usuarios.mostrar');

// ====== AUTH ======

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

//validar nombre 
Route::post('/usuarios/validar-nombre', [UsuarioController::class, 'validarNombre'])->name('usuarios.validar-nombre');

// Resetear contraseña - formulario para solicitar el enlace de restablecimiento

Route::get('/password/reset', [PasswordController::class, 'showRequestForm']);
Route::post('/password/email', [PasswordController::class, 'sendResetLink']);
Route::get('/password/reset/{token}', [PasswordController::class, 'showResetForm']);
Route::post('/password/reset', [PasswordController::class, 'resetPassword']);

Route::post('/login', [App\Http\Controllers\LoginController::class, 'login'])
    ->name('login.post'); // ← sin ->middleware('throttle:3,1')

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');



// ====== Productos ======
Route::get('/productos/registrar', [ProductoController::class, 'create'])->name('productos.registrar');
Route::get('/productos/mostrar',   [ProductoController::class, 'mostrar'])->name('productos.mostrar');

Route::post('/productos',                [ProductoController::class, 'store'])->name('productos.store');
Route::put('/productos/{id}',            [ProductoController::class, 'update'])->name('productos.update');
Route::put('/productos/{id}/inactivo',   [ProductoController::class, 'inactivo'])->name('productos.inactivo');
Route::put('/productos/{id}/activo',     [ProductoController::class, 'activo'])->name('productos.activo');

// ====== MARCAS ======

// PRIMERO las rutas ESPECÍFICAS (fijas)
Route::get('/marcas/registrar', [MarcaController::class, 'create'])->name('marcas.registrar');
Route::get('/marcas/mostrar', [MarcaController::class, 'mostrar'])->name('marcas.mostrar'); // ← ESTA PRIMERO

// LUEGO las rutas con PARÁMETROS (variables)
Route::get('/marcas/{id}', [MarcaController::class, 'show'])
    ->where('id', '[0-9]+') // Solo números
    ->name('marcas.show');; // ← ESTA DESPUÉS

// Otras rutas de marcas
Route::get('/marcas', [MarcaController::class, 'index'])->name('marcas.index');
Route::post('/marcas', [MarcaController::class, 'store'])->name('marcas.store');
Route::put('/marcas/{id}', [MarcaController::class, 'update'])->name('marcas.update');
Route::delete('/marcas/{id}', [MarcaController::class, 'destroy'])->name('marcas.destroy');
Route::post('/marcas/validar', [MarcaController::class, 'validarMarca'])->name('marcas.validar');

// ====== CATEGORIAS ======
// routes/web.php
Route::prefix('categorias')->name('categorias.')->group(function () {
    Route::get('/', [CategoriaController::class,'index'])->name('mostrarC');
    Route::get('/registrar', [CategoriaController::class,'create'])->name('registrarC');
    Route::post('/', [CategoriaController::class,'store'])->name('store');
    Route::put('/{categoria}', [CategoriaController::class,'update'])->name('update');

    // bajas / reactivación separadas (idéntico a tu patrón en usuarios)
    Route::put('/{categoria}/inactivo', [CategoriaController::class,'inactivo'])->name('inactivo');
    Route::put('/{categoria}/activo',   [CategoriaController::class,'activo'])->name('activo');

    // validación AJAX de nombre
    Route::post('/validar-nombre', [CategoriaController::class,'validarNombre'])->name('validar-nombre');

    Route::delete('/{categoria}', [CategoriaController::class,'destroy'])->name('destroy');
});


// ====== COMPRAS ======
Route::get('/compras/registrar', [ComprasController::class, 'create'])->name('compras.registrar');
Route::post('/compras', [ComprasController::class, 'store'])->name('compras.store');
Route::get('/compras/mostrar', [ComprasController::class, 'mostrar'])->name('compras.mostrar');
Route::get('/compras/{id}/detalles', [ComprasController::class, 'detalles'])->name('compras.detalles');

// ====== BACKUP========

// routes/web.php
Route::middleware(['auth', 'isAdmin'])->group(function () {

    // Página principal de respaldos
    Route::get('/backups', [BackupController::class, 'index'])
        ->name('backups.index');

    // Generar un nuevo respaldo (guardar o descargar)
    Route::post('/backups/generar', [BackupController::class, 'generar'])
        ->name('backups.generar');

    // Descargar un respaldo existente (.sql)
    Route::get('/backups/descargar', [BackupController::class, 'descargar'])
        ->name('backups.descargar');

    // Eliminar un respaldo específico (usado en modal eliminar_backup)
    Route::post('/backups/eliminar', [BackupController::class, 'destroy'])
        ->name('backups.destroy');

    // Depurar respaldos antiguos (usado en modal depurar_backup)
    Route::post('/backups/depurar', [BackupController::class, 'purgeOld'])
        ->name('backups.purge');
});

//Reportes
Route::prefix('reportes')->group(function () {
    Route::get('/', [ReporteController::class, 'mostrarReportes'])->name('reportes.mostrar');
    Route::get('/categorias', [ReporteController::class, 'categoriasReporte'])->name('reportes.categorias');
    Route::get('/marcas', [ReporteController::class, 'marcasReporte'])->name('reportes.marcas');
    Route::get('/productos', [ReporteController::class, 'productosReporte'])->name('reportes.productos');
    Route::get('/usuarios', [ReporteController::class, 'usuariosReporte'])->name('reportes.usuarios');
});

Route::get('/compras/{compra}/reporte/pdf', [CompraReporteController::class, 'detallePdf'])
     ->name('compras.reporte.detalle.pdf');

     Route::get('/productos/reporte/stock-bajo', [ReporteProductoController::class, 'stockBajoPdf'])
     ->name('productos.reporte.stock_bajo.pdf');