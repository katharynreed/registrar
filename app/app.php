<?php
    date_default_timezone_set('America/Los_Angeles');

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";


    $app = new Silex\Application();

    $app['debug'] = true;


    $server = 'mysql:host=localhost:8889;dbname=registrar';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig', array('students' => Student::getAll(), 'courses' => Course::getAll()));
    });

    $app->post("/add/course", function() use ($app) {
        $new_course = new Course($_POST['new_name'], $_POST['new_course_number']);
        $new_course->save();
        return $app['twig']->render('index.html.twig', array('students' => Student::getAll(), 'courses' => Course::getAll()));
    });

    $app->post("/add/student", function() use ($app) {
        $new_student = new Student($_POST['new_student_name'], $_POST['enrollment_date']);
        $new_student->save();
        return $app['twig']->render('index.html.twig', array('students' => Student::getAll(), 'courses' => Course::getAll()));
    });

    $app->get("/course/{id}", function($id) use ($app) {
        $found_course = Course::findCourse($id);
        $returned_students = $found_course->getStudent();
        return $app['twig']->render('course.html.twig', array('course' => $found_course, 'returned_students' => $returned_students, 'students' => Student::getAll(), 'courses' => Course::getAll()));
    });

    $app->get("/student/{id}", function($id) use ($app) {
        $found_student = Student::findStudent($id);
        $returned_courses = $found_student->getCourses();
        return $app['twig']->render('student.html.twig', array('student' => $found_student, 'returned_courses' => $returned_courses, 'students' => Student::getAll(), 'courses' => Course::getAll()));
    });

    $app->delete('/course/{id}', function ($id) use ($app) {
        $course = Course::findCourse($id);
        $course->delete();
        return $app->redirect('/');
    });

    $app->delete('/student/{id}', function ($id) use ($app) {
        $student = Student::findStudent($id);
        $student->delete();
        return $app->redirect('/');
    });

    $app->post('/add_student_to_course/{id}', function ($id) use ($app) {
        $student_id = $_POST['students'];
        $course = Course::findCourse($id);
        $course->addStudent($student_id);
        return $app->redirect('/course/'.$id);
    });

    $app->post('/add_to_course/{id}', function ($id) use ($app) {
        $course_id = $_POST['courses'];
        $student = Student::findStudent($id);
        $student->addCourse($course_id);
        return $app->redirect('/student/'.$id);
    });

    return $app;
?>
