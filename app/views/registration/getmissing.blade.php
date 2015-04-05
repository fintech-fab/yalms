@extends('registration.main')

@section('content')

    <h2>Шаг 3: Ввод оставшихся данных</h2>

    {{ Form::open([ 'url' => '/validate-missing' ]) }}

    @if (!Session::has('first_name') or Session::get('first_name')=="")

    {{ Form::text("first_name", null, [ "placeholder" => "Имя" ], Input::old("first_name")) }} <br />
    {{$errors->first('first_name')}}<br />

    @endif

    @if (!Session::has('last_name') or Session::get('last_name')=="")
    {{ Form::text("last_name", null, [ "placeholder" => "Фамилия" ], Input::old("last_name")) }} <br />
    {{$errors->first('last_name')}}<br />
    @endif

    @if (!Session::has('middle_name') or Session::get('middle_name')=="")
    {{ Form::text("middle_name", null, [ "placeholder" => "Отчество" ], Input::old("middle_name")) }} <br />
    {{$errors->first('middle_name')}}<br />
    @endif

    @if (!Session::has('email') or Session::get('email')=="")
    {{ Form::text("email", null, [ "placeholder" => "e-mail" ], Input::old("email")) }} <br />
    {{$errors->first('email')}}<br />
    @endif


    {{ Form::submit("Отправить") }}
    {{ Form::close()  }}

@stop