<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 09.02.15
 * Time: 2:18
 */

namespace Yalms\component\User;

use Validator;
use Yalms\Models\Users\UserStudent;

class UserStudentComponent
{

	const RESULT_OK = true;
	const FAILED_VALIDATION = false;

	/**
	 * Принятые данные запроса
	 *
	 * @var array
	 */
	private $input = array();

	public function __construct($input = null)
	{
		$this->input = empty($input) ? array() : array_map('trim', $input);;
	}

	/**
	 * @var string Сообщение о результате выполненных операций
	 */
	private $message = '';

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
	private $errors = array();

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
	private function setValidatorMessage($validator)
	{
		$this->message = 'Найдены ошибки при проверке данных';
		$this->errors = $validator->messages();
	}


	// сохраняет данные о студенте маршрут /student метод POST
	public function store()
	{
		$validator = Validator::make(
			$this->input,
			array(

				'user_id'                        => 'required|integer',
				'education'                      => 'required',
				'job_before'                     => 'required',
				'job_now'                        => 'required',
				'wishes_by_a_training_technique' => 'required',
				'additional_info'                => 'required',

			),
			array(
				'required' => 'Поле должно быть заполнено обязательно!',
				'integer'  => 'Поле должно быть целым положительным числом!',
			)
		);

		if ($validator->fails()) {

			$this->setValidatorMessage($validator);

			return self::FAILED_VALIDATION;

		}


		$user = UserStudent::findOrFail($this->input['user_id']);

		$user->education = $this->input['education'];
		$user->job_before = $this->input['job_before'];
		$user->job_now = $this->input['job_now'];
		$user->wishes_by_a_training_technique = $this->input['wishes_by_a_training_technique'];
		$user->additional_info = $this->input['additional_info'];

		if (!$user->save()) {
			throw new \ErrorException('Failed to save data');
		}
		$this->message = 'Данные успешно сохранены';

		return self::RESULT_OK;
	}


}