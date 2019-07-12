<?php

namespace App\Http\Controllers;

use App\Apps;
use Illuminate\Http\Request;

class ConsultaCommit extends Controller
{
    function serialziacion($app,$commit=null){
        $aplicacion = Apps::where("nombre_app",$app)->first();

        if($commit===null)
            $commits=$aplicacion->commits;
        else
            $commits=$aplicacion->commits()->where('id_co', $commit)->get();

        return response($commits);
    }
    public function httpreques(){
        Consulta::verifyPeer(false);
        $response = Consulta::get("https://api.github.com/repos/Inpsercom-IT/kia-personalizado/commits/af9c5483f9e4b2d9be64865fbd556f759353bcac",
            [
                "Authorization"     =>  "token  ".env("GITHUB_TOKEN")
            ]
        );
        return response()->json($response);
    }
}
