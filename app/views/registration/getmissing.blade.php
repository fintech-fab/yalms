@extends('registration.main')

@section('content')

    <h4>Шаг 3: Ввод оставшихся данных</h4>
    <br>

    <?php
            // не нужно так делать, не надо использовать "генератор полей" через foreach
            // и крайне не рекомендуется писать php-код в шаблонах
            // (шаблонизаторы придуманы для того, чтобы в них не было программного кода)
            // каждое поле формы лучше вывести версткой без всяких foreach
            // поверьте это не сильно трудно
            // при этом каждое поле все равно будет разное
            // (например телефон это input type="tel", а пароль это input type="password")
            // профессионалы уже давно не пишут подобные циклы, т.к. формы делаются
            // для людей, а не для роботов :-)
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
    {{ Form::hidden('social_network', Session::get('socialInfo')) }}

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-5">
            {{ Form::submit("Отправить", ['class' => 'btn btn-primary']) }}
        </div>
    </div>
    {{ Form::close()  }}

    <script type="text/javascript">
        // java-script должен находитсья в файлах *.js, а не в шаблонах вместе с кодом
        $(".MyForm").submit(function(e) {

            // лучше скрывать через .hide() а не просто делать пустым
            $('div.error').text("");
            e.preventDefault();

            var form = $('.MyForm');
            // ой как плохо :-)
            // а ведь у меня этот проект работает по ссылке http://probation-yalms.dev:8080/
            // то есть он у меня вообще не работает, т.к. настройки у меня другие
            // а на настоящем сервере это будет что то вроде lms.ru
            var url = "http://yalms.dev:8000/api/v1/user"; // the script where you handle the form input.

            $.ajax({
                type: "POST",
                dataType: 'json',
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function(data) {
//                    console.log(data);
                    if('errors' in data){
                        // шаблоны blade в javascript-коде... ой ой ой... беда :-)
                        // ответ от сервера должен обарабатываться исключительно через javascript-ом
                        // а генерацию js-кода через шаблоны blade делать это как чесать левую пятку правым ухом ))))
                        // я даже нормально прокомментировать это не могу, обсудим отдельно :)
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