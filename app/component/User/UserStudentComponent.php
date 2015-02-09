<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 09.02.15
 * Time: 2:18
 */

namespace Yalms\component\User;

use Validator;
use Yalms\Models\Users\UserStudent;

class UserStudentComponent {

	protected $rules = array(

		'user_id' => 'required|integer',
		'education' => 'required',
		'job_before' => 'required',
		'job_now' => 'required',
		'wishes_by_a_training_technique' => 'required',
		'additional_info' => 'required',

	);



	protected $input=array();

	public $validator;

	/**
	 * @param array $input
	 */
	public function __construct(Array $input)
	{
		$this->input = array_map('trim',$input);
		$this->validator = Validator::make($this->input, $this->rules);

	}

	public function store()
	{
		if (!$this->validator->fails())
		{
			$user = UserStudent::findOrFail($this->input['user_id']);

			$user->education = $this->input['education'];
			$user->job_before = $this->input['job_before'];
			$user->job_now = $this->input['job_now'];
			$user->wishes_by_a_training_technique = $this->input['wishes_by_a_training_technique'];
			$user->additional_info = $this->input['additional_info'];

			$user->save();

		}




	}



}