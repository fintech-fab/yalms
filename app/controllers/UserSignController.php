<?php

use Yalms\Models\Users\User;

class UserSignController extends \BaseController
{
    public function index()
    {
        return View::make('pages.user.index' );
    }

    public function register()
    {
        return View::make('registration');
    }


    public function  login()
    {
        $data = Input::all();

        $rules = [
            'phone' => 'required|min:11',
            'password' => 'required|min:8'
        ];

        $val = Validator::make($data, $rules);

        if( $val->fails())
        {
            return View::make('errors.validation')->with('errors', $val->messages()->toArray());
        }

        $user = User::login($data);
    }

    public function registration()
    {
        $data = Input::all();

        $rules = [
            'phone' => 'required|min:11',
            'password' => 'required|min:8|same:repeat_password',
            'repeat_password' => 'required|min:8'
        ];

        $val = Validator::make($data, $rules);

        if( $val->fails())
        {
            return View::make('errors.validation')->with('errors', $val->messages()->toArray());
        }

        $user = User::register($data);
    }

} 