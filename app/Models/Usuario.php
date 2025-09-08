<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory;

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

    public function getAuthIdentifierName()
    {
        return 'correo';
    }
    public function getAuthPassword()
    {
        return $this->contraseña;
    }

}
