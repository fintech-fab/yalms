<html lang="en">
    <head>
        {{ HTML::script('js/jquery-2.1.1.js'); }}
        {{ HTML::style('css/main.css'); }}
        <meta charset="UTF-8">
        <title>@yield('title')</title>
    </head>
    <body>
    {{--status helper from app/helpers/helpers.php--}}
    {{statusMessage()}}
