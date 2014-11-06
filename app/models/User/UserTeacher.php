<?php

namespace Yalms\Models\Users;

use Eloquent;
use Yalms\Models\Courses\Course;

/**
 * Class UserTeacher
 *
 * @property integer   $user_id
 * @property boolean   $enabled
 * @property Course[]  $courses
 * @method static UserTeacher first
 * @method static UserTeacher find($id)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserTeacher whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserTeacher whereEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserTeacher whereUpdatedAt($value)
 */
class UserTeacher extends Eloquent
{

	protected $table = 'user_teacher';

	/**
	 * Используется отношение один к одному с таблицей users.
	 * Первичный ключ, он же ещё и внешний ключ с уникальными значениями
	 */
	protected $primaryKey = 'user_id';

	public function courses()
	{
		return $this->hasMany(Course::class, 'user_teacher_id');
	}


}
