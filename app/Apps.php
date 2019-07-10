<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    protected $primaryKey       =   "id_app";

    public function duenos(){
        return $this->hasMany(Pertenece::class,"id_app","id_app");
    }

    public function commits(){
        return $this->hasMany(Commits::class,"app_co","nombre_app")->latest();
    }
}
