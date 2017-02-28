<?php
    class Course
    {
        private $name;
        private $course_number;
        private $id;

        function __construct($name, $course_number, $id=null)
        {
            $this->name = $name;
            $this->course_number = $course_number;
            $this->id = $id;
        }

        function setCourseName($new_name)
        {
          $this->name = $new_name;
        }
        function setCourseNumber($new_number)
        {
          $this->course_number = $new_number;
        }
        function getCourseName()
        {
          return $this->name;
        }
        function getCourseNumber()
        {
          return $this->course_number;
        }

        function getId()
        {
          return $this->id;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses;");
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO courses (name, course_number) VALUES ('{$this->name}', '{$this->course_number}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_names = $GLOBALS['DB']->query("SELECT * FROM courses;");
            $names = array();
            foreach($returned_names as $name)
            {
                $new_name = new Course($name['name'], $name['course_number'], $name['id']);
                array_push($names, $new_name);
            }
            return $names;
        }

        static function findCourse($id)
        {
            $find_name = $GLOBALS['DB']->query("SELECT * FROM courses WHERE id = {$id};");
            $found_name = null;
            foreach($find_name as $name)
            {
                $found_name = new Course($name['name'], $name['course_number'],$name['id']);
            }
            return $found_name;
        }

        function updateCourse($property, $update_value)
        {
            $GLOBALS['DB']->exec("UPDATE courses SET {$property} = '{$update_value}' WHERE id = {$this->getId()};");
            $this->$property = $update_value;
        }

        function deleteCourse()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()}");
        }


    }
?>
