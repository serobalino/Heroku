<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Duenos extends Model
{
    protected $table        =       "duenos";
    protected $primaryKey   =       "id_du";

    public function routeNotificationForMail($notification)
    {
        return $this->email_du;
    }
}
