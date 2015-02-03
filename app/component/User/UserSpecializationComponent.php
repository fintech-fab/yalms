<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 03.02.15
 * Time: 16:31
 */

namespace Yalms\Component\User;

use Validator;
use Yalms\Models\Users\UserAdmin;
use Yalms\Models\Users\UserStudent;
use Yalms\Models\Users\UserTeacher;

class UserSpecializationComponent {

    protected $rules = array(

	    'id' => 'required|integer',
	    'specialization' => 'required|in:admin,student,teacher',
        'enable' => 'required|in:true,false'

    );
	protected $input;

	public $validator;

	/**
	 * @param array $input
	 */
	public function __construct(Array $input)
	{

		$this->validator = Validator::make($input, $this->rules);
		$this->input = $input;


	}

	public function specialize()
	{
		if (!$this->validator->fails())
		{
			$user = null;
			switch($this->input['specialization'])
			{
				case 'teacher':

					$user = UserTeacher::findOrFail($this->input['id']);
					break;

				case 'admin':

					$user = UserAdmin::findOrFail($this->input['id']);
					break;

				case 'student':

					$user = UserStudent::findOrFail($this->input['id']);
					break;

				default:

					\App::abort(500, 'Неверные данные');
					break;

			}

			$user->enabled = $this->input['enable'] == 'true' ? 1 : 0 ;

			$user->push();


		}
	}



}