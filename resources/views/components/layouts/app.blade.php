<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        @vite('resources/js/app.js')
        @vite('resources/css/app.css')
        @livewireStyles
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

        <title>{{ $title ?? 'Page Title' }}</title>
    </head>
    <body>
        <div class="navbar-fixed">
            <nav class="nav-extended teal">
                <div class="nav-wrapper">
                    <a href="/" class="brand-logo">
                        <i class="material-icons medium mr-0">monetization_on</i> MyFinance
                    </a>
                    <a href="#" data-target="mobile-menu" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                </div>
            </nav>
        </div>

        <ul class="sidenav" id="mobile-menu">
            @auth
                <li><i class="material-icons prefix">person</i> {{ auth()->user()->login }}</li>
            @endauth

            <li><a href="/categories">Категории</a></li>
        </ul>

        <div class="container">
            {{ $slot }}
        </div>

        @livewireScripts

        <script>
            M.AutoInit();
        </script>
    </body>
</html>
