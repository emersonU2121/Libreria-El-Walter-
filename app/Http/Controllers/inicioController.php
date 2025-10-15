<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\marca;



class inicioController extends Controller
{
    public function index(){
$productosStockBajo = Producto::where('stock', '<=', 5)->get();
    $productosAgotados = Producto::where('stock', '<=', 0)->get();
    $productoTotal = Producto::count();
    $productoStockNormalCount = Producto::where('stock', '>=', 5)->count();

    $porcentajeStockNormal = $productoTotal > 0 
        ? round(($productoStockNormalCount / $productoTotal) * 100) 
        : 0;



    return view('inicio', compact(
            'productosStockBajo',
            'productosAgotados', 
            'productoTotal',
            'porcentajeStockNormal',
            'productoStockNormalCount'  
        ));
}
}