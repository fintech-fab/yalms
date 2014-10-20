<?php
use Illuminate\Support\Facades\Session;
use Yalms\Component\Shared\FlashMessageBag;

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
    $messageBag = new FlashMessageBag($_SESSION);
    dd($messageBag);
    if (Session::has('status') && Session::has('message'))

        //Есть статусное сообщение
        //FIXME Сделать перенос в переменые и их подстановку.Более читаемо будет.
        return ('<div class="' . Session::get('status') . '">' . Session::get('message') . '</div>');
}

?>