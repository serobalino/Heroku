<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Route::resource("heroku","TwilioController");
Route::post("heroku","ErroresHookController@herokuEmail");
Route::post("github","ErroresHookController@githubAction");
Route::post("twilio","TwilioController@responde");
Route::get("ciGenerator","CiGeneratorController@lista");
Route::get("validador/{ci}","CiGeneratorController@validator");
Route::get("logs","LogAppController@lista");
Route::get("log/{device}","LogAppController@consultar")->name('logdevice');
Route::post("log","LogAppController@guardar");

Route::post('{app}/{commit?}',"ConsultaCommit@serialziacion");
Route::get('downloadArtifact/{url}',"ConsultaCommit@descargaGH")->name('downloadGH');

Route::resource("error","TwilioController");
