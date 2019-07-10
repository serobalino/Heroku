<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pertenece extends Model
{
    protected $table        =       "pertenece";

    protected $with         =       ["dueno"];

    public function dueno(){
        return $this->hasOne(Duenos::class,"id_du","id_du");
    }
}
