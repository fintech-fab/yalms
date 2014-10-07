<?php

namespace Yalms\Tests\Api;


use TestCase;
use Yalms\Models\Courses\Course;


class CourseTest extends TestCase
{

    const firstCourseName = 'Астрология';
    const secondCourseName = 'Физика';

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
     * регистрируется новый человек.Функция store()
     */
    public function testCourseCreate()
    {
        $this->call('POST', 'api/v1/course', [
            'name' => CourseTest::firstCourseName,
        ]);

        $course = Course::first();

        //Адекватный ответ
        $expectedResponse = "Course " . CourseTest::firstCourseName . " been successful created";
        $this->assertResponseOk();
        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $messageResponse = $serverResponse->message;

        $this->assertEquals($messageResponse, $expectedResponse);

        $this->assertEquals(1, $course->id);
        $this->assertEquals(CourseTest::firstCourseName, $course->name);
    }

    public function testCourseUpdate()
    {

        $course = CourseTest::createCourse(CourseTest::firstCourseName);
        $url = '/api/v1/course/' . $course->id;

        //Отсылка на ресурс с данным айдишником
        $this->call('PUT', $url, [
            'name' => CourseTest::secondCourseName,
        ]);


        $expectedResponse = "Course " . CourseTest::secondCourseName . " been successful updated";
        $this->assertResponseOk();

        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $messageResponse = $serverResponse->message;

        $this->assertEquals($messageResponse, $expectedResponse);

        //Сменилось ли имя
        $course = Course::first();
        $this->assertEquals(CourseTest::secondCourseName, $course->name);


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
        $data =$response->data;

        $this->assertEquals($data[0]->name, $courseFirst->name);
        $this->assertEquals($data[1]->name, $courseSecond->name);


    }

    public function testCourseDelete()
    {

        $course = CourseTest::createCourse(CourseTest::firstCourseName);

        $url = '/api/v1/course/' . $course->id;

        $this->call('DELETE', $url);

        $expectedResponse = "Course " . CourseTest::firstCourseName . " been successful deleted";
        $serverResponse = json_decode($this->client->getResponse()->getContent());
        $messageResponse = $serverResponse->message;


        //Сверка
        $this->assertEquals($expectedResponse, $messageResponse);
        //Проверка на существование
        $this->assertEquals(Course::first(), null);


    }
}