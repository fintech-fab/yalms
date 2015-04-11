<?php

Use app\controllers\Api\User\UserController;


class UserSignController2 extends BaseController
{
	public function index()
	{
		if (!Session::has('phone')) {
			return $this->getPhoneNumber();
		}

		if (!Session::has('socialInfo')) {
			return $this->getSocialInfo();
		}

//		if (!Session::has('missingInfo')) {
			return $this->getMissingInfo();
//		}

//		$data = Session::all();

//		return View::make('registration.summary', ['data' => $data]);
	}

	public function newRegistration()
	{
		Session::flush();
		return Redirect::to('registration2');
	}

	public function getPhoneNumber()
    {
	    return View::make('registration.getphone');
    }

	public function validatePhoneNumber()
	{
		$data = Input::all();
		$rules = ['phone' => 'required|min:11|unique:users'];
		$val = Validator::make($data, $rules);

		if( $val->fails())
		{
			return Redirect::back()->withErrors($val)->withInput();
		}

		Session::put('phone', $data['phone']);
		return Redirect::to('registration2');
	}

	public function getSocialInfo()
    {
	    return View::make('registration.getsocialinfo');
    }

	public function skipSocial()
    {
	    Session::put('socialInfo', 'None');
	    return Redirect::to('registration2');
    }

	public function getMissingInfo()
    {
	    return View::make('registration.getmissing');
    }

	public function finish()
    {
	    return View::make('registration.finish');
    }

//	public function validateMissingInfo()
//	{
//		$data = Input::all();
//
//		$validateFields = array_keys($data);
//		$key = array_search('_token', $validateFields);
//		if ($key !== false)
//			unset($validateFields[$key]);
//
//		$rules = $this::validasionRules($validateFields);
//		$val = Validator::make($data, $rules);
//
//		if( $val->fails())
//		{
//			return Redirect::back()->withErrors($val)->withInput();
//		}
//
//		foreach($data as $key=>$element){
//			Session::put($key, $element);
//		}
//
//		Session::put('missingInfo', '1');
//		return Redirect::to('registration2');
//	}

//	public static function validasionRules($elements = null)
//	{
//		$allElements = [
//			'phone' => 'required|min:11',
//			'first_name' => 'required|min:5',
//			'last_name' => 'required|min:5',
//			'middle_name' => 'min:5',
//			'email' => 'email',
//		];
//		if ($elements == null){
//			return $allElements;
//		}
//		$requiredElements = array();
//		foreach($elements as $element){
//			$requiredElements[$element] = $allElements[$element];
//		}
//		return $requiredElements;
//	}

	/**
	 * Login user with facebook
	 *
	 * @return void
	 */

	public function loginWithFacebook() {

		// get data from input
		$code = Input::get( 'code' );

		// get fb service
		$fb = OAuth::consumer( 'Facebook' );

		// check if code is valid

		// if code is provided get user data and sign in
		if ( !empty( $code ) ) {

			// This was a callback request from facebook, get the token
			$token = $fb->requestAccessToken( $code );

			// Send a request with it
			$result = json_decode( $fb->request( '/me' ), true );

			$message = 'Your unique facebook user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
			echo $message. "<br/>";

			//Var_dump
			//display whole array().
//			dd($result);

			foreach($result as $key=>$element){
				Session::put($key, $element);
			}
			Session::put('socialInfo', 'Facebook');
			return Redirect::to('registration2');

		}
		// if not ask for permission first
		else {
			// get fb authorization
			$url = $fb->getAuthorizationUri();

			// return to facebook login url
			return Redirect::to( (string)$url );
		}

	}

	public function loginWithVK() {

		// get data from input
		$code = Input::get( 'code' );

		// get VK service
		$VKService = OAuth::consumer( 'Vkontakte' );

		// check if code is valid

		// if code is provided get user data and sign in
		if ( !empty( $code ) ) {

			// This was a callback request from VK, get the token
			$token = $VKService->requestAccessToken( $code );

			// Send a request with it
			$result = json_decode( $VKService->request( 'https://api.vk.com/method/users.get' ), true );


			foreach($result['response'][0] as $key=>$element){
				Session::put($key, $element);
			}
			Session::put('socialInfo', 'Vkontakte');
			return Redirect::to('registration2');

		}
		// if not ask for permission first
		else {
			// get VKService authorization
			$url = $VKService->getAuthorizationUri();

			// return to VK login url
			return Redirect::to( (string)$url );
		}
	}


}