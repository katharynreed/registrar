<?php

    require_once 'src/Course.php';

    class Student
    {
        private $student_name;
        private $date_enrolled;
        private $id;

        function __construct($student_name, $date_enrolled, $id=null)
        {
            $this->student_name = $student_name;
            $this->date_enrolled = $date_enrolled;
            $this->id = $id;
        }


        function setStudentName($new_student_name)
        {
          $this->student_name = $new_student_name;
        }
        function getStudentName()
        {
          return $this->student_name;
        }
        function getDateEnrolled()
        {
          return $this->date_enrolled;
        }

        function getId()
        {
          return $this->id;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM students;");
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO students (name, date_enrolled) VALUES ('{$this->student_name}','{$this->date_enrolled}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
            $students = array();
            foreach($returned_students as $student)
            {
                $new_student = new Student($student['name'], $student['date_enrolled'], $student['id']);
                array_push($students, $new_student);
            }
            return $students;
        }

        static function findStudent($id)
        {
            $find_student = $GLOBALS['DB']->query("SELECT * FROM students WHERE id = {$id};");
            $found_student = null;
            foreach($find_student as $student)
            {
                $found_student = new Student($student['name'], $student['date_enrolled'], $student['id']);
            }
            return $found_student;
        }

        function updateStudent($property, $update_value)
        {
            $GLOBALS['DB']->exec("UPDATE students SET {$property} = '{$update_value}' WHERE id = {$this->getId()};");
            $this->$property = $update_value;
        }

        function deleteStudent()
        {
            $GLOBALS['DB']->exec("DELETE FROM students WHERE id = {$this->getId()}");
        }


        function addCourse($course_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO enrollments (course_id, student_id) VALUES ($course_id, {$this->getId()});");
        }

        function getCourses()
        {
            $returned_courses = $GLOBALS['DB']->query("SELECT courses.* FROM students JOIN enrollments ON (students.id = enrollments.students_id) JOIN courses ON (enrollments.course_id = courses.id) WHERE students.id = {$this->getId()};");
            $courses = [];
            foreach($returned_courses as $course)
            {
                $name = $course['name'];
                $course_number = $course['course_number'];
                $id = $course['id'];
                $new_course = new Course($name, $course_number, $id);
                array_push($courses, $new_course);
            }
            return $courses;
        }
    }
?>
