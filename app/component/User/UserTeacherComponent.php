<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 09.02.15
 * Time: 2:18
 */

namespace Yalms\component\User;

use Yalms\Models\Users\UserTeacher;
use Validator;

class UserTeacherComponent {

	protected $rules = array(

		'user_id' => 'required|integer',
		'education' => 'required',
		'job_before' => 'required',
		'job_now' => 'required',
		'teacher_training' => 'required',
		'teaching_experience' => 'required|integer',
		'training_technique' => 'required',
		'requirements_to_student' => 'required',
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
			$user = UserTeacher::findOrFail($this->input['user_id']);
			$user->education = $this->input['education'];
			$user->job_before = $this->input['job_before'];
			$user->job_now = $this->input['job_now'];
			$user->teacher_training = $this->input['teacher_training'];
			$user->teaching_experience= $this->input['teaching_experience'];
			$user->training_technique = $this->input['training_technique'];
			$user->requirements_to_student = $this->input['requirements_to_student'];
			$user->additional_info = $this->input['additional_info'];

            $user->save();

		}

	}





}