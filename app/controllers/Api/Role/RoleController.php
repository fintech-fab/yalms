<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 31.01.15
 * Time: 21:32
 */

namespace app\controllers\Api\Role;

use app\controllers\Api\BaseApiController;
use app\helpers\Roles;
use Illuminate\Support\Facades\Input;


class RoleController extends BaseApiController {

	public function setRoleState(){

		$validator = Roles::getValidator();

		if ($validator->fails()) {

            return $this->responseError($validator->messages());

		}
		else{

			$userClassName = 'Yalms\Models\Users\User'.ucfirst(Input::get('role'));

			$user = $userClassName::findOrFail(Input::get('id'));

			$user->enabled = Input::get('enable') == 'true' ? 1 : 0 ;

			$user->push();

			return $this->responseSuccess("Ok");
		}



	}

}
