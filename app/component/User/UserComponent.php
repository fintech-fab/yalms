<?php
namespace Yalms\Component\User;

use Validator;
use Input;
use Mail;
use Crypt;
use URL;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;


/**
 * Class UserComponent
 *
 * @package Yalms\Component\User
 */
class UserComponent extends UserComponentBase
{

	/**
	 * @var
	 */
	public $user;

	/**
	 * @param null $input
	 */
	public function __construct($input = null)
	{
		$this->input = empty($input) ? array() : array_map('trim', $input);
	}

	/**
	 * Сообщения об ошибках при проверке данных
	 *
	 * @var array
	 */
	private $errorMessages = array(
		'required'           => 'Поле должно быть заполнено обязательно!',
		'unique'             => ':attribute с таким значением уже есть.',
		'email'              => 'Должен быть корректный адрес электронной почты.',
		'alpha_dash'         => 'Должны быть только латинские символы, цифры, знаки подчёркивания (_) и дефисы (-).',
		'confirmed'          => 'Подтверждение для :attribute не выполнено.',
		'password.confirmed' => 'Пароли не совпадают.',
		'min'                => ':attribute должен быть не меньше :min символов',
		'password.min'       => 'Пароль должен быть не меньше :min символов'
	);

	/**
	 * Массив параметров запроса со значениями по умолчанию (название параметра => значение)
	 *
	 * @var array
	 */
	private $queryParameters = array(
		'per_page'  => 30,
		'sort'      => 'created',
		'direction' => 'desc',
		'state'     => 'enabled'
	);

	/**
	 * Проверка параметров запроса множественных данных (страницы, сортировка и пр.)
	 * и установка значений по умолчанию
	 *
	 * @return object
	 */
	public function getParameters()
	{
		$validator = Validator::make(
			$this->input,
			array(
				'page'      => 'integer|min:1',
				'per_page'  => 'integer',
				'sort'      => 'in:created,updated',
				'direction' => 'in:asc,desc',
				'state'     => 'in:enabled,disabled,all'
			)
		);
		if ($validator->passes()) {
			foreach ($this->queryParameters as $parameter => $value) {
				if (!empty($this->input[$parameter])) {
					$this->queryParameters[$parameter] = $this->input[$parameter];
				}
			}
		}
		$parameters = (object)$this->queryParameters;
		$parameters->sort .= '_at';
		$parameters->state = ($parameters->state == 'enabled') ? '1' : '0';
		if ($parameters->per_page > 100) {
			$parameters->per_page = 100;
		}

		return $parameters;
	}

	/**
	 * Выдает список пользователей,
	 * с выборкой по полю enabled, либо всех пользователей.
	 *
	 * Параметры:
	 * state = enabled|disabled|all      выборка по полю "enabled", значение по умолчанию "enabled"
	 * sort = created|updated            Сортировка по полю  "created_at" или "updated_at", по умолчанию "created"
	 * direction = asc|desc              Направление сортировки, по умолчанию "desc"
	 *
	 * Для использования параметров запроса — предваритрельн вызвать "validateParameters()"
	 *
	 * Постранично. Параметры запроса страниц (не обязательные):
	 *      page — N страницы,
	 *      per_page — количество на странице.
	 *
	 * @return \Illuminate\Pagination\Paginator
	 */
	public function showUsers()
	{
		$params = $this->getParameters();

		$users = null;
		if ($params->state == 'all') {
			$users = User::with('teacher', 'student', 'admin')->orderBy($params->sort, $params->direction)->paginate(
				$params->per_page,
				array('id', 'first_name', 'middle_name', 'last_name', 'created_at', 'updated_at')
			);
		} else {
			$users = User::with('teacher', 'student', 'admin')->whereEnabled($params->state)
				->orderBy($params->sort, $params->direction)->paginate(
					$params->per_page,
					array('id', 'first_name', 'middle_name', 'last_name', 'created_at', 'updated_at')
				);
		}

		return $users;
	}

	/**
	 * Сохранение принятых данных для нового пользователя
	 *
	 * @return bool
	 * @throws \ErrorException
	 */
	public function storeNewUser()
	{
		$validator = Validator::make(
			$this->input,
			array(
				'phone'    => 'required|unique:users',
				'email'    => 'email',
				'password' => 'required|alpha_dash|min:8|confirmed'
			),
			$this->errorMessages
		);
		if ($validator->fails()) {
			$this->setValidatorMessage($validator);

			return self::FAILED_VALIDATION;
		}

		$this->user = new User;
		$this->user->phone = $this->input['phone'];
		$this->prepareToSave(array(
				'first_name',
				'middle_name',
				'last_name',
				'email',
				'password'
			)
		);

		$activeConnection = $this->user->getConnection();
		$activeConnection->beginTransaction();

		try {
			$this->saveNewUser();
			$activeConnection->commit();

		} catch (\Exception $error) {
			$activeConnection->rollBack();
			throw new \ErrorException('Failed to create new user');
		}

		/*
		 *
		 *  отправка на почту пользователя запроса для подтверждения емейла
		 *
		 */

		$confirmURL = URL::route('user/confirm', array(Crypt::encrypt($this->input['phone'])));

		$data = array('confirmURL' => $confirmURL);

		$email = $this->input['email'];

		Mail::queue('emails.confirm.email', $data, function ($message) use ($email) {
			$message->to($email)->subject('Подтверждение регистрации');
		});

		return self::RESULT_OK;
	}


	/**
	 *  Обновление данных пользователя, с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \ErrorException
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function update($id)
	{
		$this->user = User::findOrFail($id);

		$validator = Validator::make(
			$this->input,
			array(
				'email'    => 'email',
				'password' => 'alpha_dash|min:8|confirmed'
			),
			$this->errorMessages
		);
		if ($validator->fails()) {
			$this->setValidatorMessage($validator);

			return self::FAILED_VALIDATION;
		}

		$areThereData = $this->prepareToSave(array(
				'first_name',
				'middle_name',
				'last_name',
				'email',
				'password'
			)
		);
		if (!$areThereData) {
			return self::RESULT_OK;
		}

		if (!$this->user->save()) {
			throw new \ErrorException('Failed to save user data');
		}
		$this->message = 'Данные успешно сохранены';

		return self::RESULT_OK;
	}

	/**
	 * Удаление пользователя из БД.
	 *
	 * @param  int $id
	 *
	 * @throws \ErrorException
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function destroy($id)
	{
		$this->user = User::findOrFail($id);

		$activeConnection = $this->user->getConnection();
		$activeConnection->beginTransaction();

		try {
			$this->deleteUser();
			$activeConnection->commit();
		} catch (\Exception $error) {
			$activeConnection->rollBack();
			throw new \ErrorException('Failed to delete user');
		}
	}


	/**
	 * Заполнение принятых данных пользователя в модель БД
	 *
	 * @param array $fields
	 *
	 * @return bool
	 */
	private function prepareToSave($fields = array())
	{
		$areThereData = false;
		foreach ($fields as $field) {
			if (!empty($this->input[$field])) {
				$this->user->$field = $this->input[$field];
				$areThereData = true;
			}
		}
		if (!$areThereData) {
			$this->message = 'No data.';
		}

		return $areThereData;
	}

	/**
	 * @throws \ErrorException
	 */
	private function saveNewUser()
	{
		$isSaved = $this->user->save();

		$admin = new UserAdmin;
		$admin->user_id = $this->user->id;
		$isSaved &= $admin->save();

		$teacher = new UserTeacher;
		$teacher->user_id = $this->user->id;
		$isSaved &= $teacher->save();

		$student = new UserStudent;
		$student->user_id = $this->user->id;
		$isSaved &= $student->save();
		if (!$isSaved) {
			throw new \ErrorException('This user is not saved');
		}
		$this->message = 'Данные успешно сохранены';
	}

	/**
	 * @throws \ErrorException
	 */
	private function deleteUser()
	{
		$this->user->admin->enabled = false;
		$this->user->teacher->enabled = false;
		$this->user->student->enabled = false;
		$result = $this->user->push();
		$result &= $this->user->delete();
		if (!$result) {
			throw new \ErrorException('Failed to delete data');
		}
		$this->message = 'Данные успешно удалены';
	}


	/**
	 *
	 * эта функция раскодирует номер телефона
	 * создаёт соответствующий ему объект пользователя
	 * и активизирует его ( подтверждает регистрацию )
	 *
	 * @param $key
	 *
	 * @return bool
	 * @throws \ErrorException
	 */
	public function confirm($key)
	{
		$phone = Crypt::decrypt($key);

		$this->user = User::where('phone', '=', $phone)->firstOrFail();

		$this->user->enabled = 1;

		if (!$this->user->save()) {
			throw new \ErrorException('Failed to save data');
		}

		$this->message = 'Регистрация подтверждена';

		return self::RESULT_OK;
	}




	//*********************************
	// Профайлы пользователя
	//*********************************


	/**
	 *
	 *  Включение/выключение определённого профиля пользователя
	 *  $this->input['id'] идентификатор пользователя
	 *  $this->input['profile'] включаемый/выключаемый профиль (admin,student,teacher)
	 *  $this->input['enabled'] 1 - включить 0 - выключить
	 *
	 * @return bool
	 * @throws \ErrorException
	 */
	public function switchUserProfile()
	{
		$validator = Validator::make(
			$this->input,
			array(
				'id'      => 'required|integer',
				'profile' => 'required|in:admin,student,teacher',
			),
			array(
				'required' => 'Поле должно быть заполнено обязательно!',
				'integer'  => 'Поле должно быть целым положительным числом!',
				'in'       => 'Введено некорректное значение.'
			)
		);

		if ($validator->fails()) {
			$this->setValidatorMessage($validator);

			return self::FAILED_VALIDATION;

		}

		switch ($this->input['profile']) {
			case 'teacher':

				return $this->updateTeacher($this->input['id']);


			case 'admin':

				return $this->updateAdmin($this->input['id']);


			case 'student':

				return $this->updateStudent($this->input['id']);


		}


	}

	/**
	 *  Обновление данных профиля пользователя "Admin", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \ErrorException
	 */
	public function updateAdmin($id)
	{
		$admin = UserAdmin::findOrFail($id);
		if (!$this->validateProfile()) {
			return self::FAILED_VALIDATION;
		}

		$admin->enabled = $this->input['enabled'];
		if (!$admin->save()) {
			throw new \ErrorException('Failed to save data');
		}
		$this->message = 'Данные успешно сохранены';

		return self::RESULT_OK;
	}

	/**
	 *  Обновление данных профиля пользователя "студент", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \ErrorException
	 */
	public function updateStudent($id)
	{
		$student = UserStudent::findOrFail($id);
		if (!$this->validateProfile()) {
			return self::FAILED_VALIDATION;
		}

		$student->enabled = $this->input['enabled'];
		if (!$student->save()) {
			throw new \ErrorException('Failed to save data');
		}
		$this->message = 'Данные успешно сохранены';

		return self::RESULT_OK;
	}

	/**
	 *  Обновление данных профиля пользователя "учитель", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 * @throws \ErrorException
	 */
	public function updateTeacher($id)
	{
		$teacher = UserTeacher::findOrFail($id);
		if (!$this->validateProfile()) {
			return self::FAILED_VALIDATION;
		}

		$teacher->enabled = $this->input['enabled'];
		if (!$teacher->save()) {
			throw new \ErrorException('Failed to save data');
		}
		$this->message = 'Данные успешно сохранены';

		return self::RESULT_OK;
	}

	/**
	 *  Проверяет корректность поля enabled в массиве $this->input
	 *
	 *
	 * @return bool
	 */
	private function validateProfile()
	{
		$validator = Validator::make(
			$this->input,
			array('enabled' => 'required|in:0,1'),
			array(
				'required' => 'Поле должно быть заполнено обязательно!',
				'in'       => 'Введено некорректное значение.'
			)
		);
		if ($validator->fails()) {
			$this->setValidatorMessage($validator);

			return false;
		}

		return true;
	}


} 