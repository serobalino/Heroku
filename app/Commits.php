<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Unirest\Request as Consulta;

class Commits extends Model
{
    protected $primaryKey       =       "id_co";
    public $incrementing        =       false;
    protected $casts            =       [
        "respuesta_co" => "object"
    ];

    protected $appends = ['github','tiempo','artefactos'];

    public function getTiempoAttribute(){
        $campo  =   json_decode($this->attributes['respuesta_co']);
        if(!$this->attributes['ghaction_co']){
            $desde  =   new Carbon($campo->data->created_at);
            $hasta  =   new Carbon($campo->data->updated_at);
            return $hasta->diffInMinutes($desde->subMinute());
        }else{
            $desde  =   new Carbon($campo->event->head_commit->timestamp);
            $hasta  =   new Carbon();
            return $hasta->diffInMinutes($desde->subMinute());
        }
    }

    public function getGithubAttribute(){
        if(!$this->attributes['ghaction_co']){
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
        }else{
            $campo  =   json_decode($this->attributes['respuesta_co']);
            $id     =   $campo->sha;
            $repo   =   $campo->repository;
            Consulta::verifyPeer(false);
            $response = Consulta::get("https://api.github.com/repos/$repo/commits/$id",
                [
                    "Authorization"     =>  "token  ".env("GITHUB_TOKEN")
                ]
            );
            return $response->body;
        }
    }

    public function getArtefactosAttribute(){
        if(!$this->attributes['ghaction_co']){
            return ['total_count'=>0,'artifacts'=>[]];
        }else{
            $campo  =   json_decode($this->attributes['respuesta_co']);
            $id     =   $campo->run_id;
            $repo   =   $campo->repository;
            Consulta::verifyPeer(false);
            $response = Consulta::get("https://api.github.com/repos/$repo/actions/runs/$id/artifacts",
                [
                    "Authorization"     =>  "token  ".env("GITHUB_TOKEN")
                ]
            );
            foreach ($response->body->artifacts as $item){
                $item->download = route('downloadGH',Crypt::encryptString($item->archive_download_url));
                $item->size_in_mbytes = $item->size_in_bytes/1024/1024;
            }
            return $response->body;
        }
    }
}
