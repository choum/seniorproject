<?php

    require_once "Dates.php";
    require_once "SQLHelper.php";
    require_once "Course.php";

    //get the action form the request
    $action = filter_input(INPUT_POST, 'action');

    //run the appropriate function depending on request
    if (empty($action))
    {

    }
    elseif ($action == 'add_instructor')
    {
        addInstructor();
    }
    elseif ($action == 'update_instructor')
    {
        updateInstructor();
    }
    elseif ($action == 'add_class')
    {
        addClass();
    }
    elseif ($action == 'update_class')
    {
        updateClass();
    }

    //get the username from session
    if (isset($_SESSION))
    {
        $username = $_SESSION["user"];
    }
    $username = $_SESSION["user"];

    //create an istance of SQLHelper to get data from database
    //load the arrays to use on the UI
    $db = new SQLHelper();

    //create object of dates class and get the current semester and year
    $dateOB = new Dates;
    $semester_year = $dateOB->getSemesterYear();
    $date_terms = $dateOB->terms;
    $date_years = $dateOB->years;

    //create an array of instructors from the database
    $instructors = array();
    $instructor_string = $db->getInstructors();
    foreach ($instructor_string as $instructor)
    {
        $tempstr = $instructor[2] . ", " . $instructor[1];
        $temp_id = $instructor[0];
        $temp_email = $instructor[3];
        //$temp_arr = [ $temp_id => $tempstr];
        //array_push($instructors , $temp_arr);
        $instructors[$temp_id] = [$temp_id, $tempstr, $temp_email , $instructor[1] , $instructor[2]];
        asort($instructors);
    }
    $currrent_chosen_instructor = reset($instructors);

    //create an array of terms from the database
    $terms = [];
    $term_string = $db->getTerms();
    foreach ($term_string as $term)
    {
        array_push($terms, $term[0]);
    }

    //get current user
    $current_user = $db->getUser($username);

    $courses = [];
    $course_string = $db->getAllCourses();
    foreach ($course_string as $course)
    {
        $temp_course = $db->getCourse($course[0]);
        $temp_year = substr($temp_course->term, -2);
        $temp_term = substr($temp_course->term, 0, -5);
        $temp_section = $temp_course->courseSection;
        $temp_id = $temp_course->courseNumber;
        if (intval($temp_id) < 999)
        {
            $temp_id = "0" . $temp_id;
        }
        if (intval($temp_section) < 10)
        {
            $temp_section = "0" . $temp_section;
        }
        $temp_count = $dateOB->countTerm($temp_term);
        $temp_add = $temp_year . $temp_count . $temp_id . $temp_section;
        //array_push($courses , $temp_course);
        $courses[$temp_add] = $temp_course;
    }
    krsort($courses);
    $current_selected_course = $db->getCourse(reset($courses)->courseID);

    $temp_courseID = filter_input(INPUT_POST, 'course_change_select');
    if ($temp_courseID != NULL)
    {
        $current_selected_course = $db->getCourse($temp_courseID);
    }

    //if user selected a term to view for courses
    //else use current session
    $temp_sem = filter_input(INPUT_POST, 'user_selected_term');
    if ($temp_sem != NULL)
    {
        $semester_year = $temp_sem;
    }


    //create an array of courses from the database
    $current_user_courses = array();
    $courseString = $db->getCoursesByTerm($semester_year);
    foreach ($courseString as $courseID)
    {
        $temp_course = $db->getCourse($courseID[0]);
        $temp_instructor = $db->getUserByID($temp_course->teacherID);
        $temp_assignments = $db->getAssignments($courseID[0]);
        $temp_instructor_name = $temp_instructor->firstName . " " . $temp_instructor->lastName;
        $temp_arr = [$temp_course, $temp_instructor_name, $temp_assignments];
        array_push($current_user_courses, $temp_arr);
        //$current_user_courses[$temp_course] = array($temp_instructor_name , $temp_assignments)
    }


    //if any of the quesries could not run create an error to display
    if (!empty($result))
    {
        $error = $result;
    }

    //add instructor
    function addInstructor()
    {

        //get the variable from the request
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password_random = generateRandomString(25);
        //create an instance of the User class
        $temp_user = new User(substr($firstName, 0, 1) . $lastName, $password_random, $firstName, $lastName, "title", "bio", $email, "img", "linked", "site", 2, 0, "create", "");

        //create an instance of the SQLHelper class
        //add user to database
        $db = new SQLHelper();
        $result = $db->addUser($temp_user);
    }

//end of
    //update instructor
    function updateInstructor()
    {

        //get the variable from the request
        $instructorID = filter_input(INPUT_POST, 'instructorID');
        $firstName = filter_input(INPUT_POST, 'firstName');
        $lastName = filter_input(INPUT_POST, 'lastName');
        $email = filter_input(INPUT_POST, 'email');
        //create an instance of the SQLHelper class
        //update user in database
        $db = new SQLHelper();
        $result = $db->updateInstructor($instructorID, $firstName, $lastName, $email);
    }

//end of edit instructor
    //add class
    function addClass()
    {
        //get the variable from the request
        $courseID = filter_input(INPUT_POST, 'courseID');
        $sectionNumber = filter_input(INPUT_POST, 'sectionNumber');
        $term_term = filter_input(INPUT_POST, 'term');
        $term_year = filter_input(INPUT_POST, 'term-year');
        $term = $term_term . " " . $term_year;
        $classTitle = filter_input(INPUT_POST, 'classTitle');
        $classInstructorID = filter_input(INPUT_POST, 'classInstructor');
        $classDescription = filter_input(INPUT_POST, 'classDescription');

        $dateOB = new Dates;
        $close = $dateOB->getCloseDate($term);

        $db = new SQLHelper();
        $classAdminID = $db->getUser($_SESSION['user'])->id;
        //create an instance of the Course class
        $course = new Course($classTitle, $courseID, $sectionNumber, $term, $classDescription, 0, 0, $classAdminID, $classInstructorID, $close);

        //create an instance of the SQLHelper class
        //add CourseSection to database

        $result = $db->addCourse($course);

    }

// end of add class function
    //update class
    function updateClass()
    {

        //get the variable from the request
        $courseID = filter_input(INPUT_POST, 'course_change_select');
        $courseNumber = filter_input(INPUT_POST, 'courseNumber');
        $sectionNumber = filter_input(INPUT_POST, 'sectionNumber');
        $term_term = filter_input(INPUT_POST, 'term');
        $term_year = filter_input(INPUT_POST, 'term-year');
        $term = $term_term . " " . $term_year;
        $classTitle = filter_input(INPUT_POST, 'classTitle');
        $description = filter_input(INPUT_POST, 'classDescription');
        $teacherID = filter_input(INPUT_POST, 'classInstructor');

        $dateOB = new Dates;
        $close = $dateOB->getCloseDate($term);

        //create an instance of the Course class
        /* $course = new Course( $classTitle ,
          $courseID ,
          $sectionNumber ,
          $term ,
          $classDescription ,
          0 , 10 , 991 ,
          $classInstructor); */

        //create an instance of the SQLHelper class
        //update CourseSection in database
        $db = new SQLHelper();
        $classAdmin = $db->getUser($_SESSION['user'])->id;
        $result = $db->updateCourse($courseID, $classTitle, $courseNumber, $sectionNumber, $term, $description, 0, 0, $classAdmin, $teacherID, $close);
    }

// end of update class function

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

?>
