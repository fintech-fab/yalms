<?php


class UserSignController2 extends BaseController
{

	public function index()
    {
	    if (!Session::has('phone')) {
		    return $this->getPhoneNumber();
	    }

	    if (!Session::has('socialInfo')) {
		    return $this->getSocialInfo();
//		    return $this->loginWithFacebook();
	    }

		if (!Session::has('missingInfo')) {
			return $this->getMissingInfo();
		}

	    $data = Session::all();

		return View::make('registration.summary', ['data' => $data]);
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
		$rules = $this::validasionRules(['phone']);
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

//	public function validateSocialInfo()
//	{
//		$data = Input::all();
//		$rules = $this::validasionRules(['first_name', 'last_name', 'middle_name', 'email']);
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
//		Session::put('socialInfo', '1');
//		return Redirect::to('registration2');
//	}

	public function getMissingInfo()
    {
	    return View::make('registration.getmissing');
    }

	public function validateMissingInfo()
	{
		$data = Input::all();

		$validateFields = array_keys($data);
		$key = array_search('_token', $validateFields);
		if ($key !== false)
			unset($validateFields[$key]);

		$rules = $this::validasionRules($validateFields);
		$val = Validator::make($data, $rules);

		if( $val->fails())
		{
			return Redirect::back()->withErrors($val)->withInput();
		}

		foreach($data as $key=>$element){
			Session::put($key, $element);
		}

		Session::put('missingInfo', '1');
		return Redirect::to('registration2');
	}

	public static function validasionRules($elements = null)
	{
		$allElements = [
			'phone' => 'required|min:11',
			'first_name' => 'required|min:5',
			'last_name' => 'required|min:5',
			'middle_name' => 'min:5',
			'email' => 'email',
		];
		if ($elements == null){
			return $allElements;
		}
		$requiredElements = array();
		foreach($elements as $element){
			$requiredElements[$element] = $allElements[$element];
		}
		return $requiredElements;
	}

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

	public function loginWithTwitter() {

		// get data from input
		$token = Input::get( 'oauth_token' );
		$verify = Input::get( 'oauth_verifier' );

		// get twitter service
		$tw = OAuth::consumer( 'Twitter' );

		// check if code is valid

		// if code is provided get user data and sign in
		if ( !empty( $token ) && !empty( $verify ) ) {

			// This was a callback request from twitter, get the token
			$token = $tw->requestAccessToken( $token, $verify );

			// Send a request with it
			$result = json_decode( $tw->request( 'account/verify_credentials.json' ), true );

			$message = 'Your unique Twitter user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
			echo $message. "<br/>";

			//Var_dump
			//display whole array().
			dd($result);

		}
		// if not ask for permission first
		else {
			// get request token
			$reqToken = $tw->requestRequestToken();

			// get Authorization Uri sending the request token
			$url = $tw->getAuthorizationUri(array('oauth_token' => $reqToken->getRequestToken()));

			// return to twitter login url
			return Redirect::to( (string)$url );
		}
	}

	public function loginWithGoogle() {

		// get data from input
		$code = Input::get( 'code' );

		// get google service
		$googleService = OAuth::consumer( 'Google' );

		// check if code is valid

		// if code is provided get user data and sign in
		if ( !empty( $code ) ) {

			// This was a callback request from google, get the token
			$token = $googleService->requestAccessToken( $code );

			// Send a request with it
			$result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );

			$message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
			echo $message. "<br/>";

			//Var_dump
			//display whole array().
			dd($result);

		}
		// if not ask for permission first
		else {
			// get googleService authorization
			$url = $googleService->getAuthorizationUri();

			// return to google login url
			return Redirect::to( (string)$url );
		}
	}


}