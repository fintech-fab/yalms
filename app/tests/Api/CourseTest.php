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

    public function testCourseList()
    {

        $courseFirst = CourseTest::createCourse(CourseTest::firstCourseName);
        $courseSecond = CourseTest::createCourse(CourseTest::secondCourseName);

        $url = '/api/v1/course/';

        //Запрос списка
        $this->call('GET', $url);
        //Ответ
        $response = json_decode($this->client->getResponse()->getContent());

        //Сверка
        $data = $response->data;

        $this->assertEquals($data[0]->name, $courseFirst->name);
        $this->assertEquals($data[1]->name, $courseSecond->name);


    }


    public function testCourseSuccessfulCreate()
    {
        //Expected answer
        $expectedMessageResponse = "Course " . CourseTest::firstCourseName . " been successful created";
        $expectedStatusResponse = "success";

        $this->call('POST', 'api/v1/course', [
            'name' => CourseTest::firstCourseName,
        ]);

        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $serverMessageResponse = $serverResponse->message;
        $serverStatusResponse = $serverResponse->status;
        $courseId = $serverResponse->id;
        $errors = $serverResponse->errors;

        $course = Course::first();

        //Tests
        $this->assertResponseOk();
        $this->assertNotEquals(null, $courseId);//Курс создан вообще как таковой
        $this->assertEquals(1, $course->id);//Причем он единственный и первый
        $this->assertEquals(null, $errors);//При этом не было ошибок
        $this->assertEquals($serverMessageResponse, $expectedMessageResponse);//Сообщение ответа сервера совпало с ожидаемым
        $this->assertEquals($serverStatusResponse, $expectedStatusResponse);//Статус выполнения операции совпал с ожидаемым
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

    public function testCourseSuccessfulUpdate()
    {
        //Expected answer
        $expectedMessageResponse = "Course " . CourseTest::firstCourseName . " been successful updated";
        $expectedStatusResponse = "success";

        $course = CourseTest::createCourse(CourseTest::firstCourseName);
        $url = '/api/v1/course/' . $course->id;

        //Отсылка на ресурс с данным айдишником
        $this->call('PUT', $url, [
            'name' => CourseTest::secondCourseName,
        ]);


        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $ServerMessageResponse = $serverResponse->message;
        $ServerStatusResponse = $serverResponse->status;
        $errors = $serverResponse->errors;
        $course = Course::first();

        //Tests
        $this->assertResponseOk();
        $this->assertEquals(CourseTest::secondCourseName, $course->name);//Сменилось ли имя
        $this->assertEquals(null, $errors);//При этом не было ошибок
        $this->assertEquals($ServerMessageResponse, $expectedMessageResponse);
        $this->assertEquals($ServerStatusResponse, $expectedStatusResponse);

    }

    public function testCourseNotBeenUpdate()
    {
        $expectedMessageResponse = "Course not been successful updated";
        $expectedStatusResponse = "fail";
        $expectedMessageErrorsName = 'The name must be at least 5 characters.';

        $course = CourseTest::createCourse(CourseTest::firstCourseName);
        $url = '/api/v1/course/' . $course->id;


        $this->call('PUT', $url, [
            'name' => CourseTest::thirdCourseName,//Отдаем не валидное имя
        ]);


        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $serverMessageResponse = $serverResponse->message;
        $serverStatusResponse = $serverResponse->status;
        $serverMessageErrors = $serverResponse->errors->name[0];
        $course = Course::first();

        //Tests
        $this->assertResponseOk();
        $this->assertNotEquals(CourseTest::thirdCourseName, $course->name);//Имя курса не было обновлено
        $this->assertEquals($serverMessageResponse, $expectedMessageResponse);
        $this->assertEquals($serverStatusResponse, $expectedStatusResponse);
        $this->assertEquals($serverMessageErrors, $expectedMessageErrorsName);//Получили ошибки

    }

    public function testCourseSuccessfulDelete()
    {
        $expectedMessageResponse = "Course " . CourseTest::firstCourseName . " been successful deleted";
        $expectedStatusResponse = "success";

        $course = CourseTest::createCourse(CourseTest::firstCourseName);
        $url = '/api/v1/course/' . $course->id;


        $this->call('DELETE', $url);//Отсылка директивы удаления


        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $ServerMessageResponse = $serverResponse->message;
        $ServerStatusResponse = $serverResponse->status;
        $errors = $serverResponse->errors;
        $course = Course::first();

        //Tests
        $this->assertResponseOk();
        $this->assertEquals(null, $course);//Курса больше не существует
        $this->assertEquals(null, $errors);//Удаление прошло без ошибок
        $this->assertEquals($ServerMessageResponse, $expectedMessageResponse);
        $this->assertEquals($ServerStatusResponse, $expectedStatusResponse);
    }
    #TODO Добавить тесты на неудачное удаление.Для этого предусмотреть в компоненте исключения(например попыка удаления несуществующего курса)

}