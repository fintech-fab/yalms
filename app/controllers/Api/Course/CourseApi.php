<?php

namespace app\controllers\Api\Course;

use BaseController;
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
     * Получение объекта.
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
     * url: "/api/v1/course",
     * method :"POST",
     * data: {"name": "foo"}
     * });
     */
    public function store()
    {
        $courseComponent = new CourseComponent;
        $courseSuccessCreated = $courseComponent->storeCourse();

        if ($courseSuccessCreated) {
            $id = $courseComponent->courseId;
            $errors = $courseComponent->errors;
        } else {
            $id = null;
            $errors = $courseComponent->errors;
        }

        $keys = array('id', 'errors');
        $value = array($id, $errors);
        $response = array_combine($keys, $value);

        return Response::json($response);
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
        $courseComponent = new CourseComponent;
        $courseSuccessUpdated = $courseComponent->updateCourse($id);

        if ($courseSuccessUpdated) {
            $id = $courseComponent->courseId;
            $errors = $courseComponent->errors;
        } else {
            $id = null;
            $errors = $courseComponent->errors;
        }

        $keys = array('id', 'errors');
        $value = array($id, $errors);
        $response = array_combine($keys, $value);
        return Response::json($response);
    }

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
    public function destroy($id)
    {
        $courseComponent = new CourseComponent;
        $courseComponent->deleteCourse($id);

        $id = $courseComponent->courseId;
        $errors = $courseComponent->errors;

        $keys = array('id', 'errors');
        $value = array($id, $errors);
        $response = array_combine($keys, $value);
        return Response::json($response);
    }
}