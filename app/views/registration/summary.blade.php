@extends('registration.hidden_submit')

@section('content')

    <h2>Введенные данные</h2>

   {{ Form::open(array('url' => '#', 'class' => 'MyForm')) }}

    {{ Form::text('first_name', $data['first_name']); }}<br>
    {{ Form::text('last_name', $data['last_name']); }}<br>
    {{ Form::text('middle_name', $data['middle_name']); }}<br>
    {{ Form::text('phone', $data['phone']); }}<br>
    {{ Form::text('socialInfo', $data['socialInfo']); }}<br>
    {{ Form::text('password', 'password-pasword'); }}<br>
    {{ Form::text('password_confirmation', 'password-pasword'); }}<br>
    {{ Form::submit('Отправить');}}
    {{ Form::close() }}<br>



    <script type="text/javascript">
        $(".MyForm").submit(function(e) {
            e.preventDefault();

            var form = $('.MyForm');
            var url = "http://yalms.dev:8000/api/v1/user"; // the script where you handle the form input.

            $.ajax({
            type: "POST",
            dataType: 'json',
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data) {
                console.log(data);
            },
            error: function(data){
                console.log(data);
            }
            });
        });
    </script>

@stop