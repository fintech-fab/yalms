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

    static public function setParamPages()
    {
        /**
         *Распарсиваем то что нам пришло и в зависимости от этого -
         *модифицируем отдаваему страницу и количество объектов в ней
         * */
        $perPage = 10; //Количество курсов на странице по умолчанию
        $currentPage = 2;//Страница получаемая по умолчанию
        if (Input::has('per_page')) {
            //Если таки пользователь захотел видеть курсы постранично
            $perPage = Input::get('per_page');
        }

        if (Input::has('current_page')) {
            //Если нужна определеная страница
            Course::resolveConnection()->getPaginator()->setCurrentPage($currentPage);
        }

        //Отдадим обратно параметры
        return array('perPage'=>$perPage);

    }

    static public function indexCourses()
    {

        //$params = CourseComponent::setParamPages();

        //$courses = Course::paginate($params['perPage'], array('id', 'name'));

        //return $courses;
    }

    static public function updateCourse($id)
    {
      //Обновление
    }

    static public function deleteCourse($id)
    {

    }
} 