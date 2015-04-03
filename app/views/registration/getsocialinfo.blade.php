@extends('registration.main')

@section('content')

    <h2>Вход через соцсеть</h2>

    {{ Form::open([ 'url' => '/validate-social' ]) }}

    {{ Form::text("first_name", null, [ "placeholder" => "Имя" ], Input::old("first_name")) }} <br />
    {{$errors->first('first_name')}}<br />

    {{ Form::text("last_name", null, [ "placeholder" => "Фамилия" ], Input::old("last_name")) }} <br />
    {{$errors->first('last_name')}}<br />

    {{ Form::text("middle_name", null, [ "placeholder" => "Отчество" ], Input::old("middle_name")) }} <br />
    {{$errors->first('middle_name')}}<br />

    {{ Form::text("email", null, [ "placeholder" => "e-mail" ], Input::old("email")) }} <br />
    {{$errors->first('email')}}<br />


    {{ Form::submit("Отправить") }}
    {{ Form::close()  }}

@stop