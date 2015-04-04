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

Route::resource('registration2', 'UserSignController2@index');
Route::resource('validate-phone', 'UserSignController2@validatePhoneNumber');
Route::resource('new-registration', 'UserSignController2@newRegistration');
//Route::resource('validate-social', 'UserSignController2@validateSocialInfo');
Route::resource('get-facebook', 'UserSignController2@loginWithFacebook');
Route::resource('get-twitter', 'UserSignController2@loginWithTwitter');
Route::resource('get-google', 'UserSignController2@loginWithGoogle');
Route::resource('skip-social', 'UserSignController2@skipSocial');
Route::resource('validate-missing', 'UserSignController2@validateMissingInfo');

Route::group(array('prefix' => 'api/v1'), function () {

	\App::error(function (\Exception $exception, $code) {
		if ($code >= 500) {
			Log::error($exception);
		}

		return Response::json(array(
				'message'   => $exception->getMessage(),
				'errors'    => array()
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
	Route::resource('teacher', 'app\controllers\Api\User\UserTeacherController');
	Route::resource('student', 'app\controllers\Api\User\UserStudentController');

	Route::resource('course', 'app\controllers\Api\Course\CourseController');


});
