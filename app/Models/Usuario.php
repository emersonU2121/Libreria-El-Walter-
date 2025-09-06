<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuario';
    protected $primaryKey = 'idusuario';

    // Descomenta si tu tabla SÍ tiene timestamps
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'correo',
        'contraseña',   // (recomendación futura: migrar a 'password' o 'contrasena')
        'rol',
        'activo',
    ];

    protected $hidden = [
        'contraseña',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
