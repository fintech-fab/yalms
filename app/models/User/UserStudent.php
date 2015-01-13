<?php

namespace Yalms\Models\Users;

use Eloquent;

/**
 * Class UserStudent
 *
 * @property integer $user_id
 * @property boolean $enabled
 * @method static UserStudent first
 * @method static UserStudent find($id) first
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserStudent whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserStudent whereEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserStudent whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserStudent whereUpdatedAt($value)
 */
class UserStudent extends Eloquent
{

	protected $table = 'user_student';

	/**
	 * Используется отношение один к одному с таблицей users.
	 * Первичный ключ, он же ещё и внешний ключ с уникальными значениями
	 */
	protected $primaryKey = 'user_id';


}
