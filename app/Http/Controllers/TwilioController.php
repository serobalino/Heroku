<?php

namespace App\Http\Controllers;

use App\Apps;
use App\Commits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;

class TwilioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->crear($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->crear($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    private function crear($request){
        $nuevo              =   new Commits();
        $nuevo->id_co       =   $request->id;
        $nuevo->app_co      =   $request->data["app"]["name"];
        $nuevo->estado_co   =   $request->data["status"];
        $nuevo->log_co      =   $request->data["output_stream_url"];
        $nuevo->respuesta_co=   json_encode($request->all());
        $nuevo->save();

        $ap                 =   Apps::where("nombre_app",$nuevo->app_co)->first();
        if(!$ap){
            $ap             =   new Apps();
            $ap->nombre_app =   $nuevo->app_co;
            $ap->save();
        }


        if($nuevo->estado_co==="failed"){
            $sid = config("services.twilio.sid");
            $token = config("services.twilio.token");
            $client = new Client($sid, $token);

            foreach ($ap->duenos as $item){
                $message = $client->messages->create(
                    'whatsapp:'.$item->dueno->celular_du,
                    array(
                        'from' => 'whatsapp:'.config("services.twilio.from"),
                        'body' => "Hola, ".$item->dueno->alias_du." hay un error al compilar *".$nuevo->app_co."* revísalo por favor."
                    )
                );
            }

        }
        return response($request);
    }



    public function responde(Request $request)
    {

        return response($request);
    }
}
