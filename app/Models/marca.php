<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class marca extends Model
{
    use HasApiTokens, HasFactory;

    protected $table = 'marca';
    protected $primaryKey = 'idmarca';

    public $incrementing = true;
    public $timestamps = true;


    protected $fillable = [
        'nombre'
    ];

    

}
