<?php

namespace App\Http\Controllers;

use App\Apps;
use App\Commits;
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

    function vista($app,$commit=null){
        $aplicacion = Apps::where("nombre_app",$app)->first();
        if($aplicacion){
            $last = Commits::where("app_co",$app)->orderBy('created_at', 'desc')->first();
            return view("aplicacion-commit",["app"=>$aplicacion,"id"=>$commit,"last"=>$last]);
        }else{
            return abort(404);
        }
    }
}
