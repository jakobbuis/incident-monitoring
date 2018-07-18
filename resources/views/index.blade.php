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
            <feed></feed>
        </div>

        <footer class="footer">
            <div class="container">
                Built by Remko and Jakob, inspired by Marije:
                <em>"Why don't we have this yet?"</em>
            </div>
        </footer>

        <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
