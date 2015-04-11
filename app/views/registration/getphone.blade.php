@extends('registration.main')

@section('content')

<h4>Шаг 1: Ввод номера телефона</h4>
<br>

{{ Form::open([ 'url' => '/validate-phone', 'class' => 'form-horizontal']) }}
    <div class="form-group">
        {{ Form::label('Phone', 'Номер телефона', ['class' => 'col-sm-2 control-label']) }}
        <div class="col-sm-5">
            {{ Form::text("phone", null, [ "placeholder" => "Номер телефона", 'class' => 'form-control' ], Input::old("phone")) }}
        </div>
        {{$errors->first('phone')}}<br />
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            {{ Form::submit("Отправить", ['class' => 'btn btn-primary']) }}
        </div>
    </div>

{{ Form::close()  }}

@stop