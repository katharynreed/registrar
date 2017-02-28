<?php

/**
* @backupGlobals disabled
* @backupStaticAttributes disabled
*/

require_once "src/Student.php";

$server = 'mysql:host=localhost:8889;dbname=registrar_test';
$username = 'root';
$password = 'root';
$DB = new PDO($server, $username, $password);

class StudentTest extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Student::deleteAll();
    }

    function test_getters()
    {
        // Arrange
        $id = 1;
        $name = 'Dave';
        $date_enrolled = "December 1, 2000";
        $test_student = new Student ($name, $date_enrolled, $id);

        // Act
        $result = array($test_student->getId(), $test_student->getStudentName(), $test_student->getDateEnrolled());
        $expected_result = array(1, 'Dave', "December 1, 2000");

        // Assert
        $this->assertEquals($result, $expected_result);
    }

    function test_setter()
       {
           // Arrange
           $id = 1;
           $name = 'Dave';
           $date_enrolled = "December 1, 2000";
           $test_student = new Student ($name, $date_enrolled, $id);
           $new_name = 'Frank';
           $test_student->setStudentName($new_name);

           // Act
           $result = array($test_student->getId(), $test_student->getStudentName(), $test_student->getDateEnrolled());
           $expected_result = array(1, 'Frank', "December 1, 2000");

           // Assert
           $this->assertEquals($result, $expected_result);
       }

   function test_save_getAll()
       {
           // Arrange
           $name_one = 'Dave';
           $date_enrolled_one = "December 1, 2000";
           $test_student_one = new Student ($name_one, $date_enrolled_one);
           $test_student_one->save();
           $name_two = 'Frank';
           $date_enrolled_two = "December 2, 2000";
           $test_student_two = new Student ($name_two, $date_enrolled_two);
           $test_student_two->save();
           // Act
           $result = Student::getAll();
           $expected_result = array($test_student_one, $test_student_two);
           // Assert
           $this->assertEquals($result, $expected_result);
       }

       function test_findStudent()
       {
           // Arrange
           $name_one = 'Dave';
           $date_enrolled_one = "December 1, 2000";
           $test_student_one = new Student ($name_one, $date_enrolled_one);
           $test_student_one->save();
           $name_two = 'Frank';
           $date_enrolled_two = "December 2, 2000";
           $test_student_two = new Student ($name_two, $date_enrolled_two);
           $test_student_two->save();
           // Act
           $result = Student::findStudent($test_student_one->getId());

           // Assert
           $this->assertEquals($test_student_one, $result);
       }

       function test_updateStudent()
       {
           // Arrange
           $name_one = 'Dave';
           $date_enrolled = 1;
           $test_student_one = new Student ($name_one, $date_enrolled);
           $test_student_one->save();
           $property = "name";
           $update_value = "Marge";
           $result = $test_student_one->updateStudent($property, $update_value);
           // Act
           $result = Student::getAll();
           // Assert
           $this->assertEquals($update_value, $result[0]->getStudentName());
       }

       function test_deleteStudent()
       {
           // Arrange
           $name_one = 'Dave';
           $date_enrolled = "December 1, 2000";
           $test_student_one = new Student ($name_one, $date_enrolled);
           $test_student_one->save();
           $name_two = 'Frank';
           $date_enrolled = "December 2, 2000";
           $test_student_two = new Student ($name_two, $date_enrolled);
           $test_student_two->save();
           // Act
           $test_student_two->deleteStudent();
           $result = Student::getAll();
           $expected_result = array($test_student_one);

           // Assert
           $this->assertEquals($result, $expected_result);
       }

       function test_getCourses()
       {
           // Arrange
           $name = 'Intro to Business Administration';
           $course_number = "BA101";
           $test_course = new Course ($name, $course_number);
           $test_course->save();

           $returned_course = Course::getAll();
           $course_id = $returned_course[0]->getId();

           $name = 'Dave';
           $date_enrolled = "December 1, 2000";
           $test_student = new Student ($name, $date_enrolled);
           $test_student->save();
           // Act
           $test_student->addCourse($course_id);
           $result = $test_student->getCourses();
           $expected_result = array($test_course);
           // Assert
           $this->assertEquals($result, $expected_result);
       }



}

?>
