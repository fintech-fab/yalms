<?php

use Redirect;
use View;
use Yalms\Component\Course\CourseComponent;
use Yalms\Models\Courses\Course;


class CourseController extends \BaseController
{


    public function index()
    {
        $courses = CourseComponent::listCourses();
        return View::make('pages.course.index', compact('courses'));
    }

    public function create()
    {
        //Форма создания нового курса
        return View::make('pages.course.create');
    }

    public function show($id)
    {
        $course = CourseComponent::showCourse($id);
        return View::make('pages.course.show', compact('course'));
    }

    public function edit($id)
    {
        $url = URL::route('course.update', ['id' => $id]);
        $courseName = Course::find($id)->name;

        return View::make('pages.course.edit', compact('courseName', 'url'));
    }

    public function store()
    {

        $courseComponent = new CourseComponent;
        $courseSuccessCreated = $courseComponent->storeCourse();

        if ($courseSuccessCreated) {
            //Отсылка к странице новосозданомого курсу
            $id = $courseComponent->courseId;
            return Redirect::action('CourseController@show', array($id));
        } else {
            //Вертаем на страницу создания с соответствующим оповещением
            return Redirect::action('CourseController@create')
                ->withErrors($courseComponent->errors);
        }
    }


    public function update($id)
    {
        $courseComponent = new CourseComponent;
        $courseSuccessUpdated = $courseComponent->updateCourse($id);
        if ($courseSuccessUpdated) {
            //Отсылка к странице измененому курсу,увидим что мы там обновили
            return Redirect::action('CourseController@show', array($id));
        } else
            //Просмотр объекта с соответствующим оповещением
            return Redirect::action('CourseController@edit', array($id))
                ->withErrors($courseComponent->errors);
    }

    public function destroy($id)
    {
        $courseComponent = new CourseComponent;
        $courseDeleted = $courseComponent->deleteCourse($id);

        if ($courseDeleted) {
            //Отсылка к странице измененому курсу,увидим что мы там обновили
            return Redirect::action('CourseController@index');
        } else
            //Просмотр объекта с соответствующим оповещением
            return Redirect::action('CourseController@index')
                ->withErrors($courseComponent->errors);
    }
}
