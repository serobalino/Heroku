<?php

namespace App\Http\Controllers;

use App\Apps;
use App\Commits;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Unirest\Request as Consulta;

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

    function descargaGH($url=null){
        set_time_limit(60);
        if($url){
            try {
                $decrypted = Crypt::decryptString($url);
            } catch (DecryptException $e) {
                $decrypted = null;
            }
            $response = Consulta::get($decrypted,
                [
                    "Authorization"     =>  "token  ".env("GITHUB_TOKEN")
                ]
            );
            return response()->stream(function () use ($response)  {
                $response->body;
                }, 200, $response->headers);
        }else{
            return abort(404);
        }
    }
}
