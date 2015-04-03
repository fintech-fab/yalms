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

	public function validateSocialInfo()
	{
		$data = Input::all();
		$rules = $this::validasionRules(['first_name', 'last_name', 'middle_name', 'email']);
		$val = Validator::make($data, $rules);

		if( $val->fails())
		{
			return Redirect::back()->withErrors($val)->withInput();
		}

		foreach($data as $key=>$element){
			Session::put($key, $element);
		}

		Session::put('socialInfo', '1');
		return Redirect::to('registration2');
	}

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


}