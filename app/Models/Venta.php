<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'venta';
    protected $primaryKey = 'idventa';


    public $timestamps = false;


    protected $fillable = [
        'idusuario',
        'fecha',
        'total',
        'numero_factura'

    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idusuario', 'idusuario');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'idventa', 'idventa');
    }
}
