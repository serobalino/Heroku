<?php

namespace App\Http\Controllers;

use App\Apps;
use App\Commits;
use App\Duenos;
use App\Notifications\ErrorDeploy;
use App\Notifications\SlackError;
use App\Notifications\SlackErrorGh;
use App\Notifications\SlackExito;
use App\Notifications\SlackExitoGh;
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

        switch ($request->data["status"]){
            case "failed" :
                foreach ($ap->duenos as $item){
                    Notification::send($item->dueno,new ErrorDeploy($nuevo));
                }
                Notification::route('slack', env('SLACK_KEY'))->notify(new SlackError($nuevo));
                break;
            case "succeeded" :
                Notification::route('slack', env('SLACK_KEY'))->notify(new SlackExito($nuevo));
                break;
        }
        return response($request);
    }

    public function githubAction(Request $request){
        if($request->github['event']['repository']['name'] && $request->needs['name']['outputs']['name']){
            $branch = $request->needs['name']['outputs']['name'];
            $app = $request->github['event']['repository']['name']."-".$request->needs['name']['outputs']['name'];
            $repo = $request->github['repository'];
            $id = $request->github['run_id'];
            $nuevo              =   new Commits();
            $nuevo->id_co       =   $request->github['token'];
            $nuevo->app_co      =   $app;
            $nuevo->estado_co   =   $request->steps['production']['outcome'];
            $nuevo->log_co      =   "https://github.com/$repo/actions/runs/$id";
            $nuevo->ghaction_co =   true;
            $nuevo->respuesta_co=   $request->github;
            $nuevo->save();

            $ap                 =   Apps::where("nombre_app",$nuevo->app_co)->first();
            if(!$ap){
                $ap             =   new Apps();
                $ap->nombre_app =   $nuevo->app_co;
                $ap->save();
            }

            $document = $request->file('log');

            switch ($request->steps['production']['outcome']){
                case "failure" :
                    Notification::route('slack', env('SLACK_KEY'))->notify(new SlackErrorGh($nuevo,$branch,$document));
                    break;
                case "success" :
                    Notification::route('slack', env('SLACK_KEY'))->notify(new SlackExitoGh($nuevo,$branch));
                    break;
            }
            return response($request);
        }else{
            return abort(401);
        }
    }
}
