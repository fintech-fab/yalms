@extends('registration.main')

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
                    }
                    else{
                        window.location.replace("/finish");
                    }
                },
                error: function(data){
                    console.log(data);
                }
            });
        });
    </script>

@stop