<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tavo\ValidadorEc;

class CiGeneratorController extends Controller
{
    public function lista(Request $datos)
    {
        $validator = Validator::make($datos->all(), [
            'registros' => 'required|integer|max:5000',
            'codPro' => 'required|between:1,24|integer',
        ]);
        if ($validator->fails()) {
            return response()->json(['val' => false, 'request' => $datos->all(), 'errores' => $validator->errors()->all()]);
        } else {
            $validador = new ValidadorEc();
            $array = [];
            $registros = $datos->registros ? $datos->registros : 100;
            $i = 0;
            $incremento = '00000000';
            while ($i < $registros) {
                $ci = $datos->codPro < 9 ? '0' . $datos->codPro . $incremento : $datos->codPro . $incremento;
                if ($validador->validarCedula($ci)) {
                    $i++;
                    array_push($array, $ci);
                }
                $incremento = $this->incremento($incremento);

            }
            return response()->json(['val' => true, 'request' => $datos->all(), 'data' => $array]);
        }
    }

    private function incremento($numero)
    {
        $numero++;
        while (strlen((string)$numero) < 8) {
            $numero = '0' . $numero;
        }
        return $numero;
    }
}
