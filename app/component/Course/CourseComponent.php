<?php
/**
 * Created by PhpStorm.
 * User: veesot
 * Date: 9/29/14
 * Time: 1:10 AM
 */

namespace Yalms\Component\Course;



use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
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
        $perPage = 5; //Количество курсов на странице по умолчанию
        if (Input::has('per_page')) {
            //Если таки пользователь захотел видеть курсы постранично
            $perPage = Input::get('per_page');
        }

        if (Input::has('current_page')) {
            $currentPage = Input::get('current_page');
            //Если нужна определеная страница
            Course::resolveConnection()->getPaginator()->setCurrentPage($currentPage);
        }
        //Отдадим обратно параметры
        return array('perPage' => $perPage);
    }

    static public function indexCourses()
    {
        $params = CourseComponent::setParamPages();
        $courses = Course::paginate($params['perPage'], array('id', 'name'));
        return $courses;
    }

    static public function storeCourse()
    {
        //Запись и сохранение курса

        //Проверка радостей от пользователя
        $validator = Validator::make(
            array('Course name' => Input::get('name')),
            array('Course name' => array('required', 'min:5'))
        );

        if ($validator->passes()) {
            //Прошла валидация
            $course = new Course();
            $course->name = Input::get('name');
            $course->save();

            $message = 'Course ' . $course->name . ' been successful created';
            $status = 'success';
            $id = $course->id;

        } else {
            //Все немного хуже и данные не валидны
            $message = $validator->messages();
            $status = 'fail';
            $id = null;
        }

        return array(
            'message' => $message,
            'status' => $status,
            'id'=>$id
        );

    }

    static public function updateCourse($id)
    {
        //Обновление
        $course = Course::findOrFail($id);

        $validator = Validator::make(
            array('Course name' => Input::get('name')),
            array('Course name' => array('required', 'min:5'))
        );

        if ($validator->passes()) {
            $course->name = Input::get('name');
            $course->save();

            $message = 'Course ' . $course->name . ' been successful updated';
            $status = 'success';

        } else {
            $message = $validator->messages();
            $status = 'fail';
        }

        return array(
            'message' => $message,
            'status' => $status
        );

    }

    static public function showCourse($id)
    {
        //демонстрация
        $course = Course::findOrFail($id, array('name', 'id'));
        return $course;
    }

    static public function deleteCourse($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();
            if (Course::find($id) == null){
                //Курса нет более
                $message = 'Course ' . $course->name . ' been successful deleted';
                $status = 'success';
            } else {
                $message = 'Course ' . $course->name . ' not been  deleted';
                $status = 'fail';
            }
        } catch (ModelNotFoundException $e) {
            //Мимо.Нет такой страницы
            $message = 'Course not found';
            $status = 'fail';
        }

        return array(
            'message' => $message,
            'status' => $status
        );


    }
} 