<?php
/**
 * Created by PhpStorm.
 * User: veesot
 * Date: 9/29/14
 * Time: 1:10 AM
 */

namespace Yalms\Component\Course;



use Doctrine\DBAL\Types\BooleanType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yalms\Models\Courses\Course;

class CourseComponent

{

    public $errors;

    static public function setParamPages()
    {
        /**
         *Распарсиваем то что нам пришло и в зависимости от этого -
         *модифицируем отдаваему страницу и количество объектов в ней
         **/
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
        $params = CourseComponent::setParamPages();
        $courses = Course::paginate($params['perPage'], array('id', 'name'));
        return $courses;
    }

    public  function storeCourse()
    {
        /**Запись и сохранение курса
         * в случае удачи - возвращаем true,иначе false
         * @return BooleanType
         * */

        //Проверка радостей от пользователя
        $validator = Validator::make(
            array('name' => Input::get('name')),
            array('name' => array('required', 'min:5'))
        );


        if ($validator->passes()) {

            //Прошла валидация

            $course = new Course();
            $course->name = Input::get('name');
            $course->save();

            //т.к у нас тепреь есть новая модель(потенциально есть),
            //но контроллеры о ней ничего еще не знают-
            //положим упоминание о ней,чтоб они смогли прочитать и впоследствии использовать эту инфу
            Session::flash('courseId', $course->id);

            $message = 'Course ' . $course->name . ' been successful created';
            $status = 'success';
            $result = true;

        } else {

            //Все немного хуже и данные не валидны
            $message = 'Course not been successful created';
            $status = 'fail';
            $result = false;
            //Отдадим ошибки обратно клиенту
            $this->errors = $validator->messages();

        }

        Session::flash('message', $message);
        Session::flash('status', $status);


        return $result;

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