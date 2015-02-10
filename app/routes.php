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

Route::get('/', function () {
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
		   enable - устанавливаемое состояние : t1 - включён, 0 - выключен
		*/
		Route::post('user/profile', 'app\controllers\Api\User\UserController@updateProfile');


		Route::resource('teacher', 'app\controllers\Api\User\UserTeacherController');

		Route::resource('student', 'app\controllers\Api\User\UserStudentController');

		Route::resource('course', 'app\controllers\Api\Course\CourseController');


	});
