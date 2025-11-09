<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_venta';
    protected $primaryKey = 'iddetalleventa';


    public $timestamps = false;

    protected $fillable = [
        'idventa',
        'idproducto',
        'precio_total',
        'unidades'

    ];

    public function venta(){
        return $this->belongsTo(Venta::class, 'idventa');
    }

    public function getPrecioUnitarioAttribute()
    {
        if ($this->unidades > 0) {
            return $this->precio_total / $this->unidades;
        }
        return 0;
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'idproducto', 'idproducto');
    }

}
