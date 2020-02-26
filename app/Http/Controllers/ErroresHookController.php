<?php

namespace App\Http\Controllers;

use App\Apps;
use App\Commits;
use App\Duenos;
use App\Notifications\ErrorDeploy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ErroresHookController extends Controller
{
    public function herokuEmail(Request $request){
        $nuevo              =   new Commits();
        $nuevo->id_co       =   $request->id;
        $nuevo->app_co      =   $request->data["app"]["name"];
        $nuevo->estado_co   =   $request->data["status"];
        $nuevo->log_co      =   $request->data["output_stream_url"];
        $nuevo->respuesta_co=   $request->all();
        $nuevo->save();

        $ap                 =   Apps::where("nombre_app",$nuevo->app_co)->first();
        if(!$ap){
            $ap             =   new Apps();
            $ap->nombre_app =   $nuevo->app_co;
            $ap->save();
        }

        if($request->data["status"]==="failed"){
            foreach ($ap->duenos as $item){
                Notification::send($item->dueno,new ErrorDeploy($nuevo));
            }
        }
        return response($request);
    }
}
