<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $table = "logs";
    public $incrementing = null;

    const CREATED_AT = 'creado_a';
    const UPDATED_AT = null;

    protected $casts            =       [
        "trama" => "object",
        "respuesta" => "object",
        "cabecera" => "object",
    ];
}
