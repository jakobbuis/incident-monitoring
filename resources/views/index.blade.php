<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Incident monitoring</title>

        <link rel="stylesheet" type="text/css" href="{{ mix('css/app.css') }}">
    </head>
    <body>
        <div id="app">
            <div class=container>
                <nav class="navbar navbar-dark bg-dark">
                    <a class="navbar-brand" href="#">Incident monitoring</a>
                </nav>

                <feed></feed>
            </div>
        </div>

        <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
