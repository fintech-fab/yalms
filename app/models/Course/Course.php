<?php




namespace Yalms\Models\Courses;


/**
 * Class Course
 *
 * @property integer        $id
 * @property string         $name
 * @method static Course find($id)
 * @method static Course delete()
 * @method static Course save()
 * @method static Course all()
 * @method static Course findOrFail($id)
 * @method static Course paginate($id)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Lesson[] $lessons
 * @property-read \Illuminate\Database\Eloquent\Collection|\UserStudent[] $students
 * @property-read \UserTeacher $teacher
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Courses\Course whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Courses\Course whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Courses\Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Yalms\Models\Courses\Course whereUpdatedAt($value)
 */
class Course extends \Eloquent {

	protected $fillable = ['name'];
	protected $guarded = array('id');

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'courses';

	public function lessons()
	{
		return $this->hasMany('Lesson');
	}

	public function students()
	{
		return $this->belongsToMany('UserStudent');
	}

	public function teacher()
	{
		return $this->belongsTo('UserTeacher');
	}
}