<?php

namespace Yalms\Models\Users;

use Eloquent;

/**
 * Class UserAdmin
 *
 * @property integer $user_id
 * @property boolean $enabled
 * @method static UserAdmin find($id)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserAdmin whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserAdmin whereEnabled($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\UserAdmin whereUpdatedAt($value)
 */
class UserAdmin extends Eloquent
{

	protected $table = 'user_admin';

	/**
	 * Используется отношение один к одному с таблицей users.
	 * Первичный ключ, он же ещё и внешний ключ с уникальными значениями
	 */
	protected $primaryKey = 'user_id';


}
