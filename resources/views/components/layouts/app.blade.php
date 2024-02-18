<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" sizes="180x180" href="/icons/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/icons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icons/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json" />
        @vite('resources/js/app.js')
        @vite('resources/css/app.css')
        @livewireStyles
        <script src="/js/materialize.min.js"></script>
        <link rel="stylesheet" href="/css/materialize.min.css">

        <title>MyFinance</title>
    </head>
    <body>
        <div class="navbar-fixed">
            <nav class="nav-extended teal">
                <div class="nav-wrapper">
                    <a href="/" class="brand-logo">
                        <i class="material-icons medium mr-0">monetization_on</i> MyFinance
                    </a>
                    @auth()
                        <a href="#" data-target="mobile-menu" class="sidenav-trigger">
                            <i class="material-icons">menu</i>
                        </a>
                    @endauth
                </div>
            </nav>
        </div>

        @auth()
            <div class="sidenav" id="mobile-menu">
                <div class="logo teal">
                    <i class="material-icons medium mr-0">monetization_on</i> MyFinance
                </div>
                <ul class="menu">
                    <li class="user flex justify-content-between">
                        <div class="user-info">
                            <i class="material-icons prefix small mr-2">person</i> {{ auth()->user()->login }}
                        </div>
                        <div class="flex align-items-center">
                            <form  class="flex" action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="waves-effect waves-light btn-small"><i class="material-icons">exit_to_app</i></button>
                            </form>
                        </div>
                    </li>
                    <li><a href="/categories"> <i class="material-icons prefix small mr-2">list</i> Категории</a></li>
                </ul>
            </div>
        @endauth

        <div class="container">
            {{ $slot }}
        </div>

        @livewireScripts

        <script>
            M.AutoInit();
        </script>
    </body>
</html>
