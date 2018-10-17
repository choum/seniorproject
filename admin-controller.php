<?php

    //create an istance of SQLHelper to get data from database
    //load the arrays to use on the UI
    $db = new SQLHelper();
    //create an array of instructors from the database
    $instructors = $db->getInstructors();
    //create an array of terms from the database
    $terms = $db->getTerms();
    //create an array of courses from the database
    $courses = $db->getCourses();

    //get the action form the request
    $action = filter_input(INPUT_POST , 'action');

    //run the appropriate function depending on request
    if($action == 'addInstructor') {
        addInstructor();
    } elseif($action == 'updateInstructor') {
        updateInstructor();
    } elseif($action == 'addClass') {
        addClass();
    } elseif($action == 'updateClass') {
        updateClass();
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
        $instructor = new $User($instructorName)

        //create an instance of the Database class
        //add user to database
        $db = new $Database
        $result = $db.addUser($instructor);


    }//end of add instructor

    //update instructor
    function updateInstructor() {

        //get the variable from the request
        $instructorName = filter_input(INPUT_POST ,'instructorName'); 
        $changeName = filter_input(INPUT_POST ,'changeName'); 

        //create an instance of the User class
        $instructor = new $User($instructorName);

        //create an instance of the Database class
        //update user in database
        $db = new $Database
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
        $courseSection = new $CourseSection($courseID , $sectionNumber , $term , $classTitle , $classInstructor);

        //create an instance of the Database class
        //add CourseSection to database
        $db = new $Database
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
        $courseSection = new $CourseSection($courseID , $sectionNumber , $term , $classTitle , $classInstructor);

        //create an instance of the Database class
        //update CourseSection in database
        $db = new $Database
        $result = $db.updateCourseSection($courseID , $courseSection);


    }// end of update class function


?>