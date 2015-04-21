<!DOCTYPE html>
<html lang="en">
<head>
    <title>Регистрация пользователя</title>
    <meta charset="UTF-8">

    {{ HTML::style('bootstrap/css/bootstrap.css') }}
    {{ HTML::style('bootstrap/css/bootstrap-theme.css') }}

    {{ HTML::script('js/jquery-2.1.1.js') }}
    {{ HTML::script('bootstrap/js/bootstrap.min.js') }}

    <style>
        @section('styles')
        body {
            padding: 10px 30px;
        }
        .navbar{
            background-color: Gainsboro;
            border-color: Silver;
            background-image: none;
        }
        @show
    </style>

</head>
<body>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="navbar-brand">Регистрация пользователя</div>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href='/new-registration'>Новая регистрация</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

</body>
</html>