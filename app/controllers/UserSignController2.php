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
//		    return $this->loginWithFacebook();
		}

		if (!Session::has('missingInfo')) {
			return $this->getMissingInfo();
		}

		$data = Session::all();

		return View::make('registration.summary', ['data' => $data]);

		/*
				//отправка данных на регистрацию
				$r = new HttpRequest('http://yalms.dev:8000/api/v1/user', HttpRequest::METH_POST);
		//	    $r->setOptions(array('cookies' => array('lang' => 'de')));
				$r->addPostFields(array(
					'first_name' => 'Василий',
					'last_name' => 'Васильев',
					'middle_name' => 'Васильевич',
					'phone' => '79287213650',
					'email' => 'vas@mail.ru'
				));
		//	    $r->addPostFile('image', 'profile.jpg', 'image/jpeg');
				try {
					echo $r->send()->getBody();
				} catch (HttpException $ex) {
					echo $ex;
				}




				$url = 'http://yalms.dev:8000/api/v1/user';
				$data = array(
					'first_name' => 'Василий',
					'last_name' => 'Васильев',
					'middle_name' => 'Васильевич',
					'phone' => '79287213650',
					'email' => 'vas@mail.ru'
				);

				$options = array(
					'http' => array(
						'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
						'method'  => 'POST',
						'content' => http_build_query($data),
					),
				);
				$context  = stream_context_create($options);
				$result = file_get_contents($url, false, $context);

				var_dump($result);


				$content = http_build_query (array (
					'first_name' => 'Василий',
					'last_name' => 'Васильев',
					'middle_name' => 'Васильевич',
					'phone' => '79287213650',
					'email' => 'vas@mail.ru'
				));

				$context = stream_context_create (array (
					'http' => array (
						'method' => 'POST',
						'content' => $content,
					)
				));

				$result = file_get_contents('http://yalms.dev:8000/api/v1/user', null, $context);

				var_dump($result);




		$params = array(
			'first_name' => 'Василий',
			'last_name' => 'Васильев',
			'middle_name' => 'Васильевич',
			'phone' => '79287213650',
			'email' => 'vas@mail.ru'
		);
		*/
//		echo $this->httpPost(" ", $params);


	}

	/*
	public function httpPost($url, $params)
		{
			$postData = '';
			//create name value pairs seperated by &
			foreach ($params as $k => $v) {
				$postData .= $k . '=' . $v . '&';
			}
			rtrim($postData, '&');

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, count($postData));
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

			$output = curl_exec($ch);

			echo 'Ошибка curl: ' . curl_error($ch);

			curl_close($ch);

			return $output;

		}
*/


//		return View::make('registration.summary', ['data' => $data]);


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

//			$message = 'Your unique VK user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
//			echo $message. "<br/>";

			//Var_dump;
			//display whole array().
//			dd($result['response'][0]);

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