<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Unirest\Request as Consulta;

class Commits extends Model
{
    protected $primaryKey       =       "id_co";
    public $incrementing        =       false;
    protected $casts            =       [
        "respuesta_co"=>"object"
    ];

    protected $appends = ['github'];

    public function getGithubAttribute(){
        Consulta::verifyPeer(false);
        $response = Consulta::get("https://api.github.com/repos/Inpsercom-IT/kia-personalizado/commits/af9c5483f9e4b2d9be64865fbd556f759353bcac",
            [
                "Authorization"     =>  "token  ".env("GITHUB_TOKEN")
            ]
        );
        return $response->body;
    }
}
