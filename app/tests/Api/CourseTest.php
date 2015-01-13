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

    public function testCourseShow()
    {
        //Получить данные по курсу

        $course = CourseTest::createCourse(CourseTest::firstCourseName);
        $url = '/api/v1/course/' . $course->id;

        //Expected answer
        $expectedCourseId = 1;
        $expectedCourseName =CourseTest::firstCourseName;

        //Отсылка на ресурс с данным айдишником
        $this->call('GET', $url);



        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $serverCourseId = $serverResponse->id;
        $serverCourseName = $serverResponse -> name;


        //Tests
        $this->assertResponseOk();
        $this->assertEquals($expectedCourseName, $serverCourseName);//Сменилось ли имя
        $this->assertEquals($expectedCourseId, $serverCourseId);
    }

    public function testCourseSuccessfulCreate()
    {


        $this->call('POST', 'api/v1/course', [
            'name' => CourseTest::firstCourseName,
        ]);

        //Expected answer
        $expectedCourseId = 1;
        $expectedErrors = null;


        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $serverCourseId = $serverResponse->id;
        $errors = $serverResponse->errors;


        //Tests
        $this->assertResponseOk();
        $this->assertNotEquals(null, $serverCourseId);//Курс создан вообще как таковой
        $this->assertEquals($expectedCourseId, $serverCourseId);//Причем он единственный и первый
        $this->assertEquals(null, $errors);//При этом не было ошибок
    }

    public function testCourseNotBeenCreate()
    {

        $expectedMessageErrorsName = 'The name must be at least 5 characters.';

        $this->call('POST', 'api/v1/course', [
            'name' => CourseTest::thirdCourseName,
        ]);

        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $serverMessageErrors = $serverResponse->errors->name[0];
        $serverInstanceId = $serverResponse->id;

        //Tests
        $this->assertResponseOk();
        $this->assertEquals(null, Course::first());//Курс не создан вообще как таковой
        $this->assertEquals(null, $serverInstanceId);//Сервер ответил что айдишник создаваемго инстанса - неизвестен
        $this->assertEquals($serverMessageErrors, $expectedMessageErrorsName);//Получили ошибки
    }

    public function testCourseSuccessfulUpdate()
    {


        $course = CourseTest::createCourse(CourseTest::firstCourseName);
        $url = '/api/v1/course/' . $course->id;

        //Expected answer
        $expectedCourseId = 1;
        $expectedErrors = null;
        $expectedCourseName =CourseTest::secondCourseName;

        //Отсылка на ресурс с данным айдишником
        $this->call('PUT', $url, [
            'name' => $expectedCourseName,
        ]);



        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $serverCourseId = $serverResponse->id;
        $serverErrors = $serverResponse -> errors;
        $course = Course::first();

        //Tests
        $this->assertResponseOk();
        $this->assertEquals($expectedCourseName, $course->name);//Сменилось ли имя
        $this->assertEquals($expectedCourseId, $serverCourseId);
        $this->assertEquals($expectedErrors, $serverErrors);//При этом не было ошибок

    }

    public function testCourseNotBeenUpdate()
    {
        $expectedMessageErrorsName = 'The name must be at least 5 characters.';

        $course = CourseTest::createCourse(CourseTest::firstCourseName);
        $url = '/api/v1/course/' . $course->id;


        $this->call('PUT', $url, [
            'name' => CourseTest::thirdCourseName,//Отдаем не валидное имя
        ]);


        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $serverMessageErrors = $serverResponse->errors->name[0];
        $course = Course::first();

        //Tests
        $this->assertResponseOk();
        $this->assertNotEquals(CourseTest::thirdCourseName, $course->name);//Имя курса не было обновлено
        $this->assertEquals($serverMessageErrors, $expectedMessageErrorsName);//Получили ошибки

    }

    public function testCourseSuccessfulDelete()
    {
        $expectedMessageResponse = "Course been deleted";
        $expectedStatusResponse = "success";

        $course = CourseTest::createCourse(CourseTest::firstCourseName);
        $url = '/api/v1/course/' . $course->id;


        $this->call('DELETE', $url);//Отсылка директивы удаления


        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $errors = $serverResponse->errors;
        $courseId = $serverResponse->id;
        $course = Course::first();

        //Tests
        $this->assertResponseOk();
        $this->assertEquals(null, $course);//Курса больше не существует в базе
        $this->assertEquals(null, $courseId);//Сервер ответил что курса более нет
        $this->assertEquals(null, $errors);//Удаление прошло без ошибок
    }
    #TODO Добавить тесты на неудачное удаление.Для этого предусмотреть в компоненте исключения(например попыка удаления несуществующего курса)

}