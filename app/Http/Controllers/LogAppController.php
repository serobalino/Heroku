<?php

namespace App\Http\Controllers;

use App\Registro;
use Illuminate\Http\Request;

class LogAppController extends Controller
{
    public function consultar($device){
        return response()->json(Registro::where('origen',$device)->paginate(100));
    }

    public function guardar(Request $request){
        $nuevo = new Registro();
        $nuevo->origen = $request->origen;
        $nuevo->uri = $request->uri;
        $nuevo->codigo = $request->codigo;
        $nuevo->trama = @($request->trama);
        $nuevo->respuesta = @($request->respuesta);
        $nuevo->cabecera = @($request->cabecera);
        $nuevo->save();
        return response()->json($nuevo);
    }
}
