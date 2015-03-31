<?php

use Yalms\Models\Users\User;


class UserSignController2 extends BaseController
{

	public function index()
    {
//	    if (Session::has('phone')) {
//		    return Session::pull('phone');
//	    }
	    return $this->getPhoneNumber();
    }

	public function getPhoneNumber()
    {
	    Session::flush();
	    return View::make('registration.getphone');
    }

	public function validatePhoneNumber()
	{
		$data = Input::all();

		$rules = [
			'phone' => 'required|min:11',
		];

		$val = Validator::make($data, $rules);

		if( $val->fails())
		{
			return View::make('registration.getphone')->withErrors($val);
		}

		Session::put('phone', $data['phone']);
//		return Redirect::to('registration2');
		return Session::get('phone');

//		$user = User::login($data);
	}

	public function getSocialInfo()
    {


    }

	public function getMissingInfo()
    {
        //
    }





}