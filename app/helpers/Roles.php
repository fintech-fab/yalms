<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 01.02.15
 * Time: 18:32
 */

namespace app\helpers;


use Validator;
use Input;

class Roles {

    public static function getValidator(){

	    $rules = array(

		    'id' => 'required|integer',
		    'role' => 'required|in:admin,student,teacher',
		    'enable' => 'required|in:true,false'
	    );

	    return  Validator::make(Input::all(), $rules);

    }




}