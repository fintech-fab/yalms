<?php
use Illuminate\Support\Facades\Session;

/**
 * Created by PhpStorm.
 * User: veesot
 * Date: 10/2/14
 * Time: 10:01 PM
 * Служебные сообщения от контролеров пользователю.
 * Если ничего нет - ничего и не выводим
 */
function statusMessage()
{
    if (Session::has('message'))
        return ('<div class="message">' . Session::get('message') . '</div>');
}
?>