<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Duenos extends Model
{
    use Notifiable;

    protected $table        =       "duenos";
    protected $primaryKey   =       "id_du";

    public function routeNotificationForMail($notification)
    {
        return $this->email_du;
    }
}
