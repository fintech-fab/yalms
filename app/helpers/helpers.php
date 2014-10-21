<?php
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: veesot
 * Date: 10/2/14
 * Time: 10:01 PM
 */
function statusMessage()
{
    /**
     * Служебные сообщения от контролеров пользователю.
     * Если ничего нет - ничего и не выводим
     */
    if (Session::has('status') && Session::has('message'))
        //Есть статусное сообщение
        //FIXME Сделать перенос в переменые и их подстановку.Более читаемо будет.
        return ('<div class="' . Session::get('status') . '">' . Session::get('message') . '</div>');
}

function packValueToSession($list){
    /**
     * Принимаем ассоциативный массив вида ключ-значение
     * и упаковываем его в сессию до востребования
     */
    foreach ($list as $key => $value) {
        Session::flash($key, $value);
    }
}
