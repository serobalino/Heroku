<?php

namespace App\Http\Controllers;

use App\Apps;
use Illuminate\Http\Request;

class ConsultaCommit extends Controller
{
    function serialziacion($app,$commit=null){
        $aplicacion = Apps::where("nombre_app",$app)->first();
        if($aplicacion){
            if($commit===null)
                $commits=$aplicacion->commits;
            else
                $commits=$aplicacion->commits()->where('id_co', $commit)->get();
        }

        return response(@$commits,@$commits?200:500);
    }
}
