<?php
/**
 * Created by PhpStorm.
 * User: veesot
 * Date: 9/29/14
 * Time: 1:10 AM
 */

namespace Yalms\Component\Course;

use Input;
use Validator;
use Yalms\Models\Courses\Course;

class CourseComponent

{

    public $errors;
    public $courseName;
    public $courseId;

    static public function getParamPages()
    {
        /**
         *Распарсиваем то что нам пришло и в зависимости от этого -
         *модифицируем отдаваему страницу и количество объектов в ней
         **/
        $perPage = Input::get('per_page', 5);//Количество курсов на странице по умолчанию

        if (Input::has('current_page')) {
            $currentPage = Input::get('current_page');
            //Если нужна определеная страница
            Course::resolveConnection()->getPaginator()->setCurrentPage($currentPage);
        }
        //Отдадим обратно параметры
        return array('perPage' => $perPage);
    }

    static public function listCourses()
    {
        /**
         * Отдает список курсов
         * Может принимать дополнительные параметры,такие как число курсов на странице.
         * Параметры запроса страниц (не обязательные):
         *      per_page — количество на странице.
         *     *
         * @return \Illuminate\Pagination\Paginator
         */
        $params = CourseComponent::getParamPages();
        $courses = Course::paginate($params['perPage'], array('id', 'name'));
        return $courses;

    }

    /**Запись и сохранение курса
     * в случае удачи - возвращаем true,иначе false
     * */
    public function storeCourse()
    {
        //Проверка радостей от пользователя
        $validator = Validator::make(
            Input::only(['name']),
            array('name' => array('required', 'min:5'))
        );

        if ($validator->passes()) {
            //Прошла валидация
            $course = new Course();
            $course->name = Input::get('name');
            $result = $course->save();
            //Присовим часть атрибутов
            $this->courseName = $course->name;
            $this->courseId = $course->id;
            $this->errors = null;
        } else {
            //Все немного хуже и данные не валидны
            //Отдадим ошибки обратно клиенту
            $this->errors = $validator->messages();
            $result = false;
        }
        return $result;

    }

    public function updateCourse($id)
    {
        //Обновление
        $course = Course::findOrFail($id);
        $validator = Validator::make(
            array('name' => Input::get('name')),
            array('name' => array('required', 'min:5'))
        );

        if ($validator->passes()) {
            $course->name = Input::get('name');
            $result = $course->save();
            $this->courseId = $course->id;
            $this->errors = null;

        } else {
            $this->errors = $validator->messages();
            $result = false;
        }
        return $result;

    }

    static public function showCourse($id)
    {
        //демонстрация
        $course = Course::findOrFail($id, array('name', 'id'));
        return $course;
    }

    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        if (null == Course::find($id)) {
            //Нет упоминаний о курсе и удаление прошло
            $this->courseId = null;
            $result = true;
        } else {
            $this->courseId = $course->id;
            $result = false;
        }

        return $result;
    }
}