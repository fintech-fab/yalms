<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// перекошенный по форматированию кусок кода - зачем?
Route::get('/', /**
 * @return mixed
 */
	function () {
	return View::make('hello');
});
Route::get('/registration', 'UserSignController@register');
/*Route::get('/loginFacebook', 'Facebook');*/
Route::get('/loginFacebook', 'UserSignController@loginFacebook');

Route::post('/login', 'UserSignController@login');
Route::post('/registration', 'UserSignController@registration');

Route::resource('student', 'StudentController');
Route::resource('teacher', 'TeacherController');
Route::resource('course', 'CourseController');

Route::group(array('prefix' => 'api/v1'),


	function () {


		\App::error(function (\Exception $exception, $code) {
			if ($code >= 500) {
				Log::error($exception);
			}


			return Response::json(array(
				'message' => $exception->getMessage(),
				'errors'  => array()
			),
				$code
			);

		});

		\App::error(function (Illuminate\Database\Eloquent\ModelNotFoundException $exception, $code) {
			$modelName = explode("\\", $exception->getModel());

			return Response::json(array(
				'message' => array_pop($modelName) . ' not found',
				'errors'  => array()
			),
				404
			);
		});


		Route::resource('user', 'app\controllers\Api\User\UserController');


		/* Этот маршрут включает и выключает профили пользователя
		   параметры
		   id - номер пользователя в базе
		   profile - профиль пользователя ( admin student teacher )
		   enable - устанавливаемое состояние : 1 - включён, 0 - выключен
		*/
		Route::post('user/profile', 'app\controllers\Api\User\UserController@switchUserProfile');
		/**
		 *
		 * этот маршрут служит для проверки емейла и подтверждения реистрации пользователя
		 * параметр key содержит зашифрованный номер телефона пользователя
		 *
		 */
		//Route::get('user/confirm/{key}', array('as' => 'user/confirm',
		//                                 'uses' => 'app\controllers\Api\User\UserController@confirm'));

		// по форматированию, каждый следующий отступ должен быть не больше одного "таба" (4-х символов)
		// автоформат так не умеет, поэтому делать руками
		// это крайне важно для легкого чтения кода, итого делаем так:
		Route::get(
			'user/confirm/{key}',
			[
				'as'   => 'user/confirm',
				'uses' => 'app\controllers\Api\User\UserController@confirm'
			]
		);


		Route::resource('teacher', 'app\controllers\Api\User\UserTeacherController');

		Route::resource('student', 'app\controllers\Api\User\UserStudentController');

		Route::resource('course', 'app\controllers\Api\Course\CourseController');


	});
