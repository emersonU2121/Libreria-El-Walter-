<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'idcategoria';
    public $timestamps = true;

    // Si usas 'estado', agrégalo aquí y en la migración:
    // protected $fillable = ['nombre', 'estado'];
    protected $fillable = ['nombre'];

    public function scopeBuscar($q, $term) {
        if ($term) $q->where('nombre', 'like', "%{$term}%");
    }
}
