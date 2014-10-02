<?php
/**
 * Created by PhpStorm.
 * User: veesot
 * Date: 9/29/14
 * Time: 1:10 AM
 */

namespace Yalms\Component\Course;


use Illuminate\Support\Facades\Input;
use Yalms\Models\Courses\Course;

class CourseComponent
{
    /**
     * Отдает список курсов
     * Может принимать дополнительные параметры,такие как число курсов на странице.
     * Параметры запроса страниц (не обязательные):
     *      per_page — количество на странице.
     *     *
     * @return \Illuminate\Pagination\Paginator
     */

    static public function indexCourses()
    {
        $perPage = 2; //Количество курсов на странице по умолчанию
        if (Input::has('per_page')) {
            //Если таки пользователь захотел видеть курсы постранично
            $perPage = Input::get('per_page');
        }
        $courses = Course::paginate($perPage, array('id', 'name'));

        return $courses;
    }
} 