<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 12.02.15
 * Time: 19:27
 */

namespace Yalms\Component\User;


abstract class UserComponentBase
{
	const RESULT_OK = true;
	const FAILED_VALIDATION = false;

	/**
	 * Принятые данные запроса
	 *
	 * @var array
	 */
	protected $input = array();


	/**
	 * @var string Сообщение о результате выполненных операций
	 */
	protected $message = '';

	/**
	 * @return string Сообщение о результате выполненных операций
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @var array Сообщение об ошибках проверки данных (Валидатора)
	 */
	protected $errors = array();

	/**
	 * @return array Сообщение об ошибках проверки данных (Валидатора)
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Ошибки валидатора записываются в сообщение
	 *
	 * @param object $validator
	 */
	protected function setValidatorMessage($validator)
	{
		$this->message = 'Найдены ошибки при проверке данных';
		$this->errors = $validator->messages();
	}

}