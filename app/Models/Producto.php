<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Producto extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'idproducto';

    // ID manual (no autoincremental)
    public $incrementing = false;
    protected $keyType   = 'string';   // para admitir hasta 20 dígitos sin overflow

    public $timestamps = false;

    protected $fillable = [
        'idproducto',
        'imagen',        // 👈 NUEVO (va después del idproducto en BD)
        'nombre',
        'precio',
        'precio_venta',   // 👈 agregado
        'stock',
        'estado',
        'idmarca',
        'idcategoria',
    ];
}