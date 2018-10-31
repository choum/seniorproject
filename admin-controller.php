<?php

  require "Dates.php";

    //get the username from session
    $username = $_SESSION["user"];
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
      $instructor_string = $db->getInstructors(1);
      foreach ($instructor_string as $instructor) {
        array_push($instructors , $instructor[1]);
      }

      //create an array of terms from the database
      $terms = [];
      $term_string = $db->getTerms();
      foreach ($term_string as $term) {
        array_push($terms , $term[0]);
      }

      $courses = [];
      $course_string = $db->getAllCourses();
      foreach ($course_string as $course) {
        array_push($courses , $db->getCourse($course[0])->courseTitle);
      }

      //get current user
      $current_user = $db->getUser($username);
      $current_user_name = $current_user->firstName . " " . $current_user ->lastName;

      //create an array of courses from the database
      $current_user_course = [];
      $courseString = $db->getCoursesInstructorTerm( $current_user->id , $semester_year);
      foreach ($courseString as $courseID ) {
        array_push($current_user_courses, $db->getCourse($courseID['id']));
      }

      //get the action form the request
      $action = filter_input(INPUT_POST , 'action');



    //run the appropriate function depending on request
    if (empty($action)) {

    }
      elseif($action == 'addInstructor') {
        addInstructor();
    } elseif($action == 'updateInstructor') {
        updateInstructor();
    } elseif($action == 'addClass') {
        addClass();
    } elseif($action == 'updateClass') {
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
        $result = $db.addUser($instructor);


    }//end of add instructor

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
        $result = $db.updateUser($instructorName , $instructor);


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
        $courseSection = new CourseSection($courseID , $sectionNumber , $term , $classTitle , $classInstructor);

        //create an instance of the SQLHelper class
        //add CourseSection to database
        $db = new SQLHelper();
        $result = $db.addCourseSection($courseSection);


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
        $result = $db.updateCourseSection($courseID , $courseSection);


    }// end of update class function

    function viewProject() {
      //get project variables
      $course = filter_input(INPUT_POST, 'Course');
      $assignment = filter_input(INPUT_POST, 'Assignment');

       //sql statement
      include 'project-view.php';
    }

?>
