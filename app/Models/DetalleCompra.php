<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compra';
    protected $primaryKey = 'iddetallecompra';

    protected $fillable = [
    'idcompra',
    'idproducto',
    'precio_total',
    'unidades',
    'concepto'
];

    //relaciones 
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'idcompra');
    }

    public function producto()
    {
        return $this-> belongsTo(Producto::class, "idproducto");
    }

    public function getPrecioUnitarioAttribute()
{
    if ($this->unidades > 0) {
        return $this->precio_total / $this->unidades;
    }
    return 0;
}
}
