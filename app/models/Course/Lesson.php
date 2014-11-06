<?php

namespace Yalms\Models\Courses;

/**
 * Yalms\Models\Courses\Lesson
 *
 * @property-read \Course $courses
 * @property-read \Illuminate\Database\Eloquent\Collection|\Exam[] $exams
 */
class Lesson extends \Eloquent {

	protected $fillable = [];

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'course_lessons';

	public function courses()
	{
		return $this->belongsTo('Course');
	}

	public function exams()
	{
		return $this->hasMany('Exam');
	}
}