<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Unirest\Request as Consulta;

class Commits extends Model
{
    protected $primaryKey       =       "id_co";
    public $incrementing        =       false;
    protected $casts            =       [
        "respuesta_co" => "object"
    ];

    protected $appends = ['github'];

    public function getGithubAttribute(){
        $campo  =   json_decode($this->attributes['respuesta_co']);
        $url    =   explode("/",$campo->data->source_blob->url);
        $id     =   $campo->data->source_blob->version;
        Consulta::verifyPeer(false);
        $response = Consulta::get("https://api.github.com/repos/$url[3]/$url[4]/commits/$id",
            [
                "Authorization"     =>  "token  ".env("GITHUB_TOKEN")
            ]
        );
        return $response->body;
    }
}
