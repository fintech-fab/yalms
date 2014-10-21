<?php

namespace Yalms\Tests\Api;

use TestCase;
use Yalms\Models\Courses\Course;


class CourseTest extends TestCase
{

    const firstCourseName = 'Астрология';
    const secondCourseName = 'Физика';
    const thirdCourseName = 'ОБЖ';

    public function createCourse($courseName)
    {
        $course = new Course();
        $course->name = $courseName;
        $course->save();
        return $course;
    }

    public function setUp()
    {

        parent::setUp();
        Course::truncate();

    }

    /**
     * Создается новый курс.Функция store()
     */

    public function testCourseSuccessfulCreate()
    {
        //Expected answer
        $expectedMessageResponse = "Course " . CourseTest::firstCourseName . " been successful created";
        $expectedStatusResponse = "success";

        $this->call('POST', 'api/v1/course', [
            'name' => CourseTest::firstCourseName,
        ]);

        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $ServerMessageResponse = $serverResponse->message;
        $ServerStatusResponse = $serverResponse->status;
        $courseId = $serverResponse->id;
        $errors = $serverResponse->errors;

        $course = Course::first();

        //Tests
        $this->assertResponseOk();
        $this->assertNotEquals(null, $courseId);//Курс создан вообще как таковой
        $this->assertEquals(1, $course->id);//Причем он единственный и первый
        $this->assertEquals(null, $errors);//При этом не было ошибок
        $this->assertEquals($ServerMessageResponse, $expectedMessageResponse);//Сообщение ответа сервера совпало с ожидаемым
        $this->assertEquals($ServerStatusResponse, $expectedStatusResponse);//Статус выполнения операции совпал с ожидаемым
        $this->assertEquals(CourseTest::firstCourseName, $course->name);//Название курса совпадает с ожидаемым
    }

    public function testCourseNotBeenCreate()
    {
        $expectedMessageResponse = 'Course not been successful created';
        $expectedStatusResponse = "fail";
        $expectedMessageErrorsName = 'The name must be at least 5 characters.';

        $this->call('POST', 'api/v1/course', [
            'name' => CourseTest::thirdCourseName,
        ]);

        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $serverMessageResponse = $serverResponse->message;
        $serverStatusResponse = $serverResponse->status;
        $serverMessageErrors = $serverResponse->errors->name[0];

        //Tests
        $this->assertResponseOk();
        $this->assertEquals(null, Course::first());//Курс не создан вообще как таковой
        $this->assertEquals($serverMessageResponse, $expectedMessageResponse);
        $this->assertEquals($serverStatusResponse, $expectedStatusResponse);
        $this->assertEquals($serverMessageErrors, $expectedMessageErrorsName);//Получили ошибки
    }
}