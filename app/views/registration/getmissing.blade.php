@extends('registration.main')

{{--@section('js')--}}
    {{--<script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>--}}
{{--@stop--}}

@section('content')

    <h4>Шаг 3: Ввод оставшихся данных</h4>
    <br>

    <?php
        $items = array();
        $items['last_name'] = 'Фамилия';
        $items['first_name'] = 'Имя';
        $items['middle_name'] = 'Отчество';
        $items['phone'] = 'Телефон';
        $items['email'] = 'Электронная почта';
        $items['password'] = 'Пароль';
        $items['password_confirmation'] = 'Повтор пароля';
    ?>

{{--    {{ Form::open(array('url' => '#', 'class' => 'MyForm')) }}--}}
    {{ Form::open([ 'url' => '#', 'class' => 'MyForm form-horizontal']) }}


    @foreach ($items as $key => $item)
        <div class="form-group">
            {{ Form::label($key, $item, ['class' => 'col-sm-2 control-label']) }}
            <div class="col-sm-5">
                {{ Form::text($key,Session::get($key), [ 'class' => 'form-control' ]) }}
            </div>
            <div class="error" id={{ $key }}></div>
        </div>
    @endforeach


    {{--<div class="form-group">--}}
        {{--{{ Form::label('last_name', 'Фамилия', ['class' => 'col-sm-2 control-label']) }}--}}
        {{--<div class="col-sm-5">--}}
            {{--{{ Form::text("last_name",Session::get('last_name'), [ "placeholder" => "Фамилия", 'class' => 'form-control' ], Input::old("phone")) }}--}}
        {{--</div>--}}
        {{--<div class="error" id="last_name"></div>--}}
    {{--</div>--}}



    {{--{{ Form::text("last_name", Session::get('last_name'), [ "placeholder" => "Фамилия" ], Input::old("last_name")) }} <br />--}}
    {{--<div class="error" id="last_name"></div>--}}

    {{--{{ Form::text("first_name", Session::get('first_name'), [ "placeholder" => "Имя" ], Input::old("first_name")) }} <br />--}}
    {{--<div class="error" id="first_name"></div>--}}

    {{--{{ Form::text("middle_name", Session::get('middle_name'), [ "placeholder" => "Отчество" ], Input::old("middle_name")) }} <br />--}}
    {{--<div class="error" id="middle_name"></div>--}}

    {{--{{ Form::text("phone", Session::get('phone'), [ "placeholder" => "Телефон" ], Input::old("middle_name")) }} <br />--}}
    {{--<div class="error" id="phone"></div>--}}

    {{--{{ Form::text("email", Session::get('email'), [ "placeholder" => "e-mail" ], Input::old("email")) }} <br />--}}
    {{--<div class="error" id="email"></div>--}}

    {{--{{ Form::text("password", Null, [ "placeholder" => "пароль" ]) }} <br />--}}
    {{--<div class="error" id="password"ошибки нет></div>--}}

    {{--{{ Form::text("password_confirmation", Null, [ "placeholder" => "повторить пароль" ]) }} <br />--}}
    {{--<div class="error" id="password_confirmation"></div>--}}

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            {{ Form::submit("Отправить", ['class' => 'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::close()  }}

    <script type="text/javascript">
        $(".MyForm").submit(function(e) {

            $('div.error').text("");
            e.preventDefault();

            var form = $('.MyForm');
            var url = "http://yalms.dev:8000/api/v1/user"; // the script where you handle the form input.

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function(data) {
//                    console.log(data);
                    if('errors' in data){

                        @foreach ($items as $key => $item)
                            $('div#{{ $key }}').text(data.errors.{{ $key }});
                        @endforeach


//                                            $('div#last_name').text(data.errors.last_name);
//                        $('div#first_name').text(data.errors.first_name);
//                        $('div#middle_name').text(data.errors.middle_name);
//                        $('div#phone').text(data.errors.phone);
//                        $('div#email').text(data.errors.email);
//                        $('div#password').text(data.errors.password);
//                        $('div#password_confirmation').text(data.errors.password_confirmation);
                    }
                    else{
                        window.location.replace("/finish");
                    }
                },
                error: function(data){
                    console.log(data);
//                    $('div.error').text("ошибка!");
                }
            });
        });
    </script>

@stop