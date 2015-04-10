@extends('registration.main')

@section('js')
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
@stop

@section('content')

    <h2>Шаг 3: Ввод оставшихся данных</h2>

    {{ Form::open(array('url' => '#', 'class' => 'MyForm')) }}

    {{ Form::text("last_name", Session::get('last_name'), [ "placeholder" => "Фамилия" ], Input::old("last_name")) }} <br />
    <div class="error" id="last_name"></div>

    {{ Form::text("first_name", Session::get('first_name'), [ "placeholder" => "Имя" ], Input::old("first_name")) }} <br />
    <div class="error" id="first_name"></div>

    {{ Form::text("middle_name", Session::get('middle_name'), [ "placeholder" => "Отчество" ], Input::old("middle_name")) }} <br />
    <div class="error" id="middle_name"></div>

    {{ Form::text("phone", Session::get('phone'), [ "placeholder" => "Телефон" ], Input::old("middle_name")) }} <br />
    <div class="error" id="phone"></div>

    {{ Form::text("email", Session::get('email'), [ "placeholder" => "e-mail" ], Input::old("email")) }} <br />
    <div class="error" id="email"></div>

    {{ Form::text("password", Null, [ "placeholder" => "пароль" ]) }} <br />
    <div class="error" id="password"ошибки нет></div>

    {{ Form::text("password_confirmation", Null, [ "placeholder" => "повторить пароль" ]) }} <br />
    <div class="error" id="password_confirmation"></div>

    {{ Form::submit("Отправить") }}
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
                        $('#last_name').text(data.errors.last_name);
                        $('#first_name').text(data.errors.first_name);
                        $('#middle_name').text(data.errors.middle_name);
                        $('#phone').text(data.errors.phone);
                        $('#email').text(data.errors.email);
                        $('#password').text(data.errors.password);
                        $('#password_confirmation').text(data.errors.password_confirmation);
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