<?php

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "src/Course.php";

$server = 'mysql:host=localhost:8889;dbname=registrar_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class CourseTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Course::deleteAll();
    }

    function test_getters()
    {
        // Arrange
        $name = 'Intro to Business Administration';
        $course_number = "BA101";
        $id = 1;
        $test_course = new Course ($name, $course_number, $id);

        // Act
        $result = array ($test_course->getCourseName(), $test_course->getCourseNumber(),$test_course->getId());
        $expected_result = array('Intro to Business Administration', 'BA101', 1);

        // Assert
        $this->assertEquals($result, $expected_result);
    }

    function test_setter()
    {
        // Arrange
        $name = 'Intro to Business Administration';
        $course_number = "BA101";
        $id = 1;
        $test_course = new Course ($name, $course_number, $id);
        $test_course->setCourseName('Crazy ish');

        // Act
        $result = array ($test_course->getCourseName(), $test_course->getCourseNumber(),$test_course->getId());
        $expected_result = array('Crazy ish', 'BA101', 1);

        // Assert
        $this->assertEquals($result, $expected_result);
    }

    function test_save_getAll()
    {
        // Arrange
        $name_one = 'Intro to Business Administration';
        $course_number = "BA101";
        $test_course_one = new Course ($name_one, $course_number);
        $test_course_one->save();
        $name_two = 'Financial Accounting';
        $course_number_two = 'ACT210';
        $test_course_two = new Course ($name_two, $course_number_two);
        $test_course_two->save();

        // Act
        $result = Course::getAll();
        $expected_result = array($test_course_one, $test_course_two);

        // Assert
        $this->assertEquals($result, $expected_result);
    }

    function test_findCourse()
    {
        // Arrange
        $name_one = 'Intro to Business Administration';
        $course_number = "BA101";
        $test_course_one = new Course ($name_one, $course_number);
        $test_course_one->save();
        $name_two = 'Financial Accounting';
        $course_number_two = 'ACT210';
        $test_course_two = new Course ($name_two, $course_number_two);
        $test_course_two->save();
        // Act
        $result = Course::findCourse($test_course_one->getId());

        // Assert
        $this->assertEquals($test_course_one, $result);
    }

    function test_updateCourse()
    {
        // Arrange
        $name = 'Intro to Business Administration';
        $course_number = "BA101";
        $test_course = new Course ($name, $course_number);
        $test_course->save();
        $property = "name";
        $update_value = "Intro to a class";
        $result = $test_course->updateCourse($property, $update_value);
        // Act
        $result = Course::getAll();
        $expected_result = new Course($update_value, $course_number);
        // Assert
        $this->assertEquals($update_value, $result[0]->getCourseName());
    }

    function test_deleteCourse()
    {
        // Arrange
        $name_one = 'Intro to Business Administration';
        $course_number = "BA101";
        $test_course_one = new Course ($name_one, $course_number);
        $test_course_one->save();
        $name_two = 'Financial Accounting';
        $course_number_two = 'ACT210';
        $test_course_two = new Course ($name_two, $course_number_two);
        $test_course_two->save();
        // Act
        $test_course_two->deleteCourse();
        $result = Course::getAll();
        $expected_result = array($test_course_one);

        // Assert
        $this->assertEquals($result, $expected_result);
    }




}

?>
