<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 17.02.15
 * Time: 22:57
 */

namespace Yalms\Component\Mailer;

use Crypt;
use URL;
use Mail;
use Illuminate\Mail\Message;

/**
 * Class MailerComponent
 *
 * предназначен для отправки различных почтовых сообщений
 *
 * @package Yalms\Component\Mailer
 */
class MailerComponent
{

	/**
	 *
	 *  функция предназначена для отправки письма, необходимого
	 *  для подтверждения регистрации
	 *
	 * @param $key
	 * @param $email
	 */
	public static function userConfirm($key, $email)
	{
		$confirmURL = URL::route('user/confirm', array(Crypt::encrypt($key)));

		$data = array('confirmURL' => $confirmURL);

		Mail::queue('emails.confirm.email', $data, function (Message $message) use ($email) {
			$message->to($email)->subject('Подтверждение регистрации');
		});

	}


}