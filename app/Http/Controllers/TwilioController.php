<?php

namespace App\Http\Controllers;

use App\Commits;
use Illuminate\Http\Request;

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
        return response($request);
    }



    public function responde(Request $request)
    {
        
        return response($request);
    }
}
