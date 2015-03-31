@extends('registration.main')

@section('content')

<h2>Ввод номера телефона</h2>

{{ Form::open([ 'url' => '/validatePhoneNumber' ]) }}

{{ Form::label('Phone', 'Номер телефона') }}
{{ Form::text("phone", null, [ "placeholder" => "Номер телефона" ], Input::old("phone")) }} <br />
{{$errors->first('phone')}}<br />

{{ Form::submit("Отправить") }}
{{ Form::close()  }}

@stop