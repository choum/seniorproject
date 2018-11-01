<?php

  require_once "Dates.php";
  require_once "SQLHelper.php";
  require_once "Course.php";

    //get the username from session
    if(isset($_SESSION)) {
      $username = $_SESSION["user"];
    }

    //create object of dates class and get the current semester and year
    $dateOB = new Dates;
    $semester_year = $dateOB->getSemesterYear();

    //if user selected a term to view for courses
    //else use current session
    if(isset($_SESSION['selected_term'])) {
      $semester_year = $_SESSION['selected_term'];
    }

      //create an istance of SQLHelper to get data from database
      //load the arrays to use on the UI
      $db = new SQLHelper();


      //create an array of instructors from the database
      $instructors = [];
      $instructor_string = $db->getInstructors(2);
      foreach ($instructor_string as $instructor) {
        $tempstr = $instructor[1] . " " . $instructor[2];
        $temp_id = $instructor[0];
        $temp_arr = [$tempstr , $temp_id];
        array_push($instructors , $temp_arr);
      }

      //create an array of terms from the database
      $terms = [];
      $term_string = $db->getTerms();
      foreach ($term_string as $term) {
        array_push($terms , $term[0]);
      }

      //get current user
      $current_user = $db->getUser($username);
      $current_user_name = $current_user->firstName . " " . $current_user ->lastName;

      $courses = [];
      $course_string = $db->getAllCourses();
      foreach ($course_string as $course) {
        array_push($courses , $db->getCourse($course[0])->courseTitle);
      }


      //create an array of courses from the database
      $current_user_courses = [];
      $courseString = $db->getCoursesByTerm(  $semester_year);
      foreach ($courseString as $courseID ) {
        $temp_course = $db->getCourse($courseID[0]);
        $temp_instructor = $db->getUserByID($temp_course->teacherID);
        $temp_assignments = $db->getAssignments($courseID[0]);
        $temp_instructor_name = $temp_instructor->firstName . " " . $temp_instructor->lastName;
        $temp_arr = [$temp_course , $temp_instructor_name , $temp_assignments];
        array_push($current_user_courses, $temp_arr);
      }

      //get the action form the request
      $action = filter_input(INPUT_POST , 'action');



    //run the appropriate function depending on request
    if (empty($action)) {

    }
      elseif($action == 'add_instructor') {
        addInstructor();
    } elseif($action == 'update_instructor') {
        updateInstructor();
    } elseif($action == 'add_class') {
        addClass();
    } elseif($action == 'update_class') {
        updateClass();
    } else if($action == 'project') {
       viewProject();
    }

    //if any of the quesries could not run create an error to display
    if(!empty($result)) {
        $error = $result;
    }



    //add instructor
    function addInstructor() {

        //get the variable from the request
        $instructorName = filter_input(INPUT_POST ,'instructorName');

        //create an instance of the User class
        $instructor = new User($instructorName);

        //create an instance of the SQLHelper class
        //add user to database
        $db = new SQLHelper();
        $result = $db->addUser($instructor);


    }//end of

    //update instructor
    function updateInstructor() {

        //get the variable from the request
        $instructorName = filter_input(INPUT_POST ,'instructorName');
        $changeName = filter_input(INPUT_POST ,'changeName');

        //create an instance of the User class
        $instructor = new User($instructorName);

        //create an instance of the SQLHelper class
        //update user in database
        $db = new SQLHelper();
        $result = $db->updateUser($instructorName , $instructor);


    }//end of edit instructor

    //add class
    function addClass() {

        //get the variable from the request
        $courseID = filter_input(INPUT_POST ,'courseID');
        $sectionNumber = filter_input(INPUT_POST ,'sectionNumber');
        $term = filter_input(INPUT_POST ,'term');
        $classTitle = filter_input(INPUT_POST ,'classTitle');
        $classInstructor = filter_input(INPUT_POST ,'classInstructor');

        //create an instance of the Course class
        $course = new Course( $classTitle , $courseID , $sectionNumber , $term , $classTitle , "0" , "0" , "0" , $classInstructor);

        //create an instance of the SQLHelper class
        //add CourseSection to database
        $db = new SQLHelper();
        $result = $db->addCourse($course);

    }// end of add class function

    //update class
    function updateClass() {

        //get the variable from the request
        $courseID = filter_input(INPUT_POST ,'courseID');
        $sectionNumber = filter_input(INPUT_POST ,'sectionNumber');
        $term = filter_input(INPUT_POST ,'term');
        $classTitle = filter_input(INPUT_POST ,'classTitle');
        $classInstructorr = filter_input(INPUT_POST ,'classInstructor');

        //create an instance of the Course class
        $courseSection = new CourseSection($courseID , $sectionNumber , $term , $classTitle , $classInstructor);

        //create an instance of the SQLHelper class
        //update CourseSection in database
        $db = new SQLHelper();
        $result = $db->updateCourseSection($courseID , $courseSection);


    }// end of update class function

    function viewProject() {
      //get project variables
      $course = filter_input(INPUT_POST, 'Course');
      $assignment = filter_input(INPUT_POST, 'Assignment');

       //sql statement
      include 'project-view.php';
    }


?>
