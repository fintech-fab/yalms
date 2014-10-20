<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
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

    public function store()
    {
        $courseComponent = new CourseComponent;
        $courseSuccessCreated = $courseComponent->storeCourse();
        if($courseSuccessCreated){
            //Отсылка к странице новосозданомого курсу
            return Redirect::action('CourseController@show');
        }
        else
            //Вертаем на страницу создания с соответствующим оповещением
            return Redirect::action('CourseController@create')->withErrors();
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

    public function update($id)
    {
        $result = CourseComponent::updateCourse($id);
        $message = $result['message'];
        $status = $result['status'];
        //Покажем что мы там обновили
        return Redirect::action('CourseController@show', array($id))
            ->with('message', $message)
            ->with('status',$status);
    }

    public function destroy($id)
    {
        $result = CourseComponent::deleteCourse($id);
        $message = $result['message'];
        $status = $result['status'];

        //Отправим на заглавную страницу всех курсов
        //после редиректа от функции удаления.
        //Тогда у нас есть некое статусное сообщение($message),
        //которое необходимо отрисовать на странице.

        return Redirect::action('CourseController@index')
            ->with('message', $message)
            ->with('status',$status);
    }
}
