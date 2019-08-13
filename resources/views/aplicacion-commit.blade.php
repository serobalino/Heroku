<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <title>{{ config('app.name', 'Log') }}</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app" class="container">
    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">{{$app->nombre_app}}</h1>
        </div>
    </div>
    <div class="">
        <commits></commits>
    </div>
</div>
<script src="{{ mix('js/app.js') }}" defer></script>
</body>
</html>
