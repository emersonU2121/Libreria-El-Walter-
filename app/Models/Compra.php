<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';
    protected $primaryKey = 'idcompra';

    protected $fillable = [
        'concepto',
        'fecha',
        'idusuario'
    ];

  

  public function usuario()
  {
    return $this->belongsTo(Usuario::class, 'idusuario');
  }

   public function detalles()
    {
        return $this->hasMany(DetalleCompra::class, 'idcompra'); 
    }

    // Accessor para el total de la compra
    public function getTotalAttribute()
    {
        return $this->detalles->sum('precio_total');
    }

    // Accessor para la cantidad de productos diferentes
    public function getCantidadProductosAttribute()
    {
        return $this->detalles->count();
    }

    // Accessor para unidades totales
    public function getUnidadesTotalesAttribute()
    {
        return $this->detalles->sum('unidades');
    }

    // Método adicional que ya tenías
    public function obtenerTotalProductos()
    {
        return $this->detalles()->sum('precio_total');
    }

}
