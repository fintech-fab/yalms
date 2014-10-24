<?php
namespace Yalms\Component\User;

use Input;
use Yalms\Models\Users\User;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;
use DB;
use Validator;


class UserComponent
{

	/**
	 * @var null|User
	 */
	public $user = null;

	/**
	 * Принятые данные запроса
	 *
	 * @var array
	 */
	private $input = array();

	public function __construct($input = null)
	{
		$this->input = empty($input) ? array() : $input;
	}

	/**
	 * @var string|array Сообщение о результате выполненных операций
	 */
	public $message = '';

	/**
	 * Статус результата
	 *
	 * @var int
	 */
	public $status = 200;

	/**
	 * Сообщения об ошибках при проверке данных
	 *
	 * @var array
	 */
	private $errorMessages = array(
		'required'   => 'Поле должно быть заполнено обязательно!',
		'unique'     => ':attribute с таким значением уже есть.',
		'email'      => 'Должен быть корректный адрес электронной почты.',
		'alpha_dash' => 'Должны быть только латинские символы, цифры, знаки подчёркивания (_) и дефисы (-).',
		'confirmed'  => 'Пароли не совпадают.'
	);

	/**
	 * Выдает список пользователей,
	 * с выборкой по полю enabled, либо всех пользователей.
	 * Постранично. Параметры запроса страниц (не обязательные):
	 *      page — N страницы,
	 *      per_page — количество на странице.
	 *
	 * @param string $controlEnabled Выборка пользователей по значению поля "enabled". Значения параметра: 1|0|all
	 *
	 * @return \Illuminate\Pagination\Paginator
	 */
	static public function showUsers($controlEnabled = 'all')
	{
		$perPage = 30; //Количество строк на странице по умолчанию
		if (Input::has('per_page')) {
			$perPage = Input::get('per_page');
		}

		$users = null;
		if ($controlEnabled == 'all') {
			$users = User::paginate($perPage, array('id', 'first_name', 'middle_name', 'last_name'));
		} elseif ($controlEnabled == '1' || $controlEnabled == '0') {
			$users = User::whereEnabled($controlEnabled)
				->paginate($perPage, array('id', 'first_name', 'middle_name', 'last_name'));
		}

		return $users;
	}

	/**
	 * Сохранение принятых данных для нового пользователя
	 *
	 * @return bool
	 */
	public function storeNewUser()
	{
		$validator = Validator::make(
			$this->input,
			array(
				'phone'    => 'required|unique:users',
				'email'    => 'email',
				'password' => 'required|alpha_dash|confirmed'
			),
			$this->errorMessages
		);
		if ($validator->fails()) {
			$this->message = $validator->messages();

			return false;
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

		$activeConnection = DB::connection();
		$activeConnection->beginTransaction();

		try {
			if ($this->user->save()) {
				$isSaved = true;

				$admin = new UserAdmin;
				$admin->user_id = $this->user->id;
				$isSaved &= $admin->save();

				$teacher = new UserTeacher;
				$teacher->user_id = $this->user->id;
				$isSaved &= $teacher->save();

				$student = new UserStudent;
				$student->user_id = $this->user->id;
				$isSaved &= $student->save();

				if ($isSaved) {
					$activeConnection->commit();
					$this->message = 'This user is saved';

					return true;
				}
			}
			$activeConnection->rollBack();
			$this->message = 'This user is not saved';
		} catch (\Exception $error) {
			$this->message = $error->getMessage();
			$activeConnection->rollBack();
		}

		return false;
	}


	/**
	 *  Обновление данных пользователя, с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function update($id)
	{
		$this->user = User::find($id);
		if (empty($this->user->id)) {
			$this->message = 'User not found';
			$this->status = 404;

			return false;
		}

		$validator = Validator::make(
			$this->input,
			array(
				'email'    => 'email',
				'password' => 'alpha_dash|confirmed'
			),
			$this->errorMessages
		);
		if ($validator->fails()) {
			$this->message = $validator->messages();

			return false;
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
			return false;
		}

		$result = $this->user->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}

	/**
	 * Удаление пользователя из БД.
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function destroy($id)
	{
		$this->user = User::find($id);
		if (empty($this->user->id)) {
			$this->message = 'User not found';

			return false;
		}

		$activeConnection = DB::connection();
		$activeConnection->beginTransaction();

		try {
			$isChanged = false;
			if (isset($this->user->admin->enabled) && $this->user->admin->enabled) {
				$this->user->admin->enabled = false;
				$isChanged = true;
			}
			if (isset($this->user->teacher->enabled) && $this->user->teacher->enabled) {
				$this->user->teacher->enabled = false;
				$isChanged = true;
			}
			if (isset($this->user->student->enabled) && $this->user->student->enabled) {
				$this->user->student->enabled = false;
				$isChanged = true;
			}
			if ($isChanged) {
				$this->user->push();
			}

			$result = $this->user->delete();
			$this->message = ($result) ? 'Data deleted successfully' : 'Failed to delete data';
			$activeConnection->commit();
		} catch (\Exception $error) {
			$result = false;
			$this->message = $error->getMessage();
			$activeConnection->rollBack();
		}

		return $result;
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
			$this->message = 'No required data.';
		}

		return $areThereData;
	}


	//*********************************
	// Профайлы пользователя
	//*********************************

	/**
	 *  Обновление данных профиля пользователя "Admin", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function updateAdmin($id)
	{
		$admin = UserAdmin::find($id);
		if (empty($admin->user_id)) {
			$this->message = 'Admin not found';
			$this->status = 404;

			return false;
		}
		if (!$this->validateProfile()) {
			return false;
		}

		$admin->enabled = $this->input['enabled'];
		$result = $admin->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}

	/**
	 *  Обновление данных профиля пользователя "студент", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function updateStudent($id)
	{
		$student = UserStudent::find($id);
		if (empty($student->user_id)) {
			$this->message = 'Student not found';
			$this->status = 404;

			return false;
		}
		if (!$this->validateProfile()) {
			return false;
		}

		$student->enabled = $this->input['enabled'];
		$result = $student->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}

	/**
	 *  Обновление данных профиля пользователя "учитель", с указанным id
	 *
	 * @param  int $id
	 *
	 * @return bool
	 */
	public function updateTeacher($id)
	{
		$teacher = UserTeacher::find($id);
		if (empty($teacher->user_id)) {
			$this->message = 'Teacher not found';
			$this->status = 404;

			return false;
		}
		if (!$this->validateProfile()) {
			return false;
		}

		$teacher->enabled = $this->input['enabled'];
		$result = $teacher->save();
		$this->message = ($result) ? 'Data saved successfully' : 'Failed to save data';

		return $result;
	}

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
			$this->message = $validator->errors();

			return false;
		}

		return true;
	}


} 