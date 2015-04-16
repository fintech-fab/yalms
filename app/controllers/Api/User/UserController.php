<?php
namespace app\controllers\Api\User;

use app\controllers\Api\BaseApiController;
use Input;
use Response;
use Yalms\Component\User\UserComponent;
use Yalms\Models\Users\User;


class UserController extends BaseApiController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$userComp = new UserComponent(Input::all());

		return Response::json(
			$userComp->showUsers()
		);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		return Response::json(array(
			'edit_fields'     => array(
				'last_name'             => 'Фамилия',
				'first_name'            => 'Имя',
				'middle_name'           => 'Отчество',
				'email'                 => 'Электронная почта',
				'phone'                 => 'Номер телефона',
				'password'              => 'Пароль',
				'password_confirmation' => 'Подтверждение пароля'
			),
			'required_fields' => array(
				'first_name',
				'phone',
				'password',
				'password_confirmation'
			)
		));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$userComp = new UserComponent(Input::all());

		if ($userComp->storeNewUser() == UserComponent::FAILED_VALIDATION) {
			return $this->responseError($userComp->getMessage(), $userComp->getErrors());
		}

		return $this->show($userComp->user->id, 201);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 * @param int  $statusHttp
	 *
	 * @return Response
	 */
	public function show($id, $statusHttp = 200)
	{
		$user = User::with('teacher', 'student', 'admin')
			->findOrFail($id, array('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone'));

		return Response::json(['user' => $user], $statusHttp);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::findOrFail($id, array('id', 'first_name', 'middle_name', 'last_name', 'email', 'phone'));

		$fields = array(
			'last_name'             => 'Фамилия',
			'first_name'            => 'Имя',
			'middle_name'           => 'Отчество',
			'email'                 => 'Электронная почта',
			'password'              => 'Пароль',
			'password_confirmation' => 'Подтверждение пароля'
		);

		return Response::json(array(
				'user'        => $user,
				'edit_fields' => $fields
			)
		);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function update($id)
	{
		$userComponent = new UserComponent(\Input::all());

		if ($userComponent->update($id) == UserComponent::FAILED_VALIDATION) {
			return $this->responseError($userComponent->getMessage(), $userComponent->getErrors());
		}

		return $this->show($id);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$userComponent = new UserComponent();
		$userComponent->destroy($id);

		return $this->responseSuccess($userComponent->getMessage());
	}


	public function changeProfile()
	{
		$userComp = new UserComponent(Input::only('id', 'role', 'flag'));


		if ($userComp->changeProfile() == UserComponent::FAILED_VALIDATION) {
			return $this->responseError($userComp->getMessage(), $userComp->getErrors());
		}

		return $this->show($userComp->user->id, 201);
	}

}
