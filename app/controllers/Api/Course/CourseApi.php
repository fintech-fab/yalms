<?php

namespace app\controllers\Api\Course;

use BaseController;
use Illuminate\Support\Facades\Session;
use Response;
use Yalms\Component\Course\CourseComponent;


/**API-интерфейс на Course**/
class CourseController extends BaseController
{

    /**
     * Получение списка объектов.
     * Пример запроса
     * $.ajax({
     * url: "/api/v1/course/"
     * });
     */
    public function index()
    {
        $courses = CourseComponent::listCourses();
        return Response::json($courses);
    }

    /**
     * Обновление объекта.
     * Пример запроса
     * $.ajax({
     * url: "/api/v1/course",
     * method :"POST",
     * data: {"name": "foo"}
     * });
     */
    public function store()
    {
        $courseComponent = new CourseComponent;
        $courseSuccessCreated = $courseComponent->storeCourse();
        $status = Session::get('status');
        $message = Session::get('message');

        if ($courseSuccessCreated) {
            $id = Session::get('courseId');
            $errors = null;
        }else{
            $id = null;
            $errors = $courseComponent->errors;
        }

        $keys = array('id', 'status', 'message','errors');
        $value = array($id, $status,$message,$errors);
        $response = array_combine($keys,$value);

        return Response::json($response);
    }


    /**
     * Получение конкретного объекта.
     * Пример запроса
     * $.ajax({
     * url: "/api/v1/course/1"
     * });
     * @param $id
     * @return Response::json
     */
    public function show($id)
    {
        $course = CourseComponent::showCourse($id);
        return Response::json($course);
    }

    /**
     * Обновление объекта.
     * Пример запроса
     * $.ajax({
     * url: "/api/v1/course/7",
     * method :"PUT",
     * data: {"name": "bar"}
     * });
     * @param $id
     * @return Response::json
     */
    public function update($id)
    {
        $result = CourseComponent::updateCourse($id);
        return Response::json($result);
    }


    public function destroy($id)
    {
        /**
         * Удаление объекта.
         * Пример запроса
         * $.ajax({
         * url: "/api/v1/course/7",
         * method :"DELETE"
         * });
         * @param $id
         * @return Response::json
         */
        $result = CourseComponent::deleteCourse($id);
        return Response::json($result);
    }
}