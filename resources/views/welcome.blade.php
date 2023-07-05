<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv='cache-control' content ='no-cache'>
        <meta http-equiv='expires' content ='0'>
        <meta http-equiv='pragma' content ='no-cache'>

        <title>Vehicle Diary</title>
        
        <!-- icons-->
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
      
    </head>
    <body class="antialiased">

       <div id="app"></div>
       
       @vite('resources/js/app.js')

    </body>
</html>
