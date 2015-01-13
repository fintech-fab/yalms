<?php

namespace Yalms\Models\Users;

use Eloquent;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\UserTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;


/**
 * Class User
 *
 * @property integer     $id
 * @property string      $first_name
 * @property string      $middle_name
 * @property string      $last_name
 * @property string      $email
 * @property string      $phone
 * @property string      $password
 * @property string      $remember_token
 * @property boolean     $enabled
 * @property UserStudent $student
 * @property UserTeacher $teacher
 * @property UserAdmin   $admin
 * @method static User whereEnabled($boolean)
 * @method static User wherePhone($phone)
 * @method static User findOrFail($id)
 * @method static User find($id)
 * @method static User first()
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User whereFirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User whereLastName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User whereMiddleName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Users\User whereUpdatedAt($value)
 */
class User extends Eloquent implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait, SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token', 'enabled', 'email', 'phone');

	protected $dates = ['deleted_at'];

	public function student()
	{
		return $this->hasOne(UserStudent::class);
	}

	public function teacher()
	{
		return $this->hasOne(UserTeacher::class);
	}

	public function admin()
	{
		return $this->hasOne(UserAdmin::class);
	}

    public static function login( $data )
    {
        /*
         * логиним юзера*/
    }

    public static function register( $data )
    {
        /*
         * Запись юзера в базу*/
    }


}
