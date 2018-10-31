<?php

    include_once("./SQLHelper.php");
    include_once("./User.php");
    include_once("./CourseSection.php");

    $querys = new SQLHelper();
    
    $return = $querys->getSubmissionsOfCourse(1113);
    print_r($return);
    if(is_array($return)){
        echo "<br/>";
        foreach($return as $studentAssignment):
            if(is_array($studentAssignment)){
                echo "<br/>";
                foreach($studentAssignment as $key=>$column):
                    if(is_numeric($key)){
                        echo $column . ", ";
                    }
                endforeach;
            }
            else{
                foreach($return as $key=>$column):
                    if(is_numeric($key)){
                        echo $column . ", ";
                    }
                endforeach;
            }
            
        endforeach;
    }
////for testing add user function
//    /*$user = new User("username" . rand(0, 100), "password", "firstName",
//            "lastName", "title", "bio", "image", "linkedin", "website", 1, 0,
//            date("Y/m/d"), NULL);
//    $return = $querys->addUser($user);
//    echo $return;*/
//    echo "<br/>Temporarily disabling user creation";
////for testing update user function
//    $return = $querys->updateUser(992, "AH", "AB", "AS", "AF");
//    echo "<br/>" . $return;
////for testing get user function
//    $return = $querys->getUser("username");
//    if (is_object($return))
//        echo "<br/>Returning only UID, FN, LN: " .
//        $return->id . ", " . $return->firstName . ", " . $return->lastName;
//    else
//        echo "<br/>" . $return;
////for testing add instructor function, which performs the same as user currently
//    /*$instructor = new User("username" . rand(0, 100), "password", "firstName",
//            "lastName", "title", "bio", "image", "linkedin", "website", 2, 0,
//            date("Y/m/d"), NULL);
//    $return = $querys->addInstructor($instructor);
//    echo "<br/>" . $return;*/
//    echo "<br/> Temporarily disabling instructor creation";
////for testing update instructor function
//    $return = $querys->updateInstructor(993, "firstName1", "lastName2");
//    echo "<br/>" . $return;
////for testing get instructors function, assumed role # for instructors is 2
//    $return = $querys->getInstructors(2);
//    if (is_array($return))
//    {
//        foreach ($return as $instructor):
//            echo "<br/>";
//            foreach ($instructor as $key => $column):
//                if (is_numeric($key) == FALSE)
//                    echo $column . ", ";
//            endforeach;
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
////for testing get instructor function, based on userID
//    $return = $querys->getInstructor(1264);
//    if (is_object($return))
//        echo "<br/>Returning only UID, FN, LN: " .
//        $return->id . ", " . $return->firstName . ", " . $return->lastName;
//    else
//        echo "<br/>" . $return;
////for testing create course function
//    /*$course = new Course("Intro to Accounting", 127, 012, "Fall 2018",
//      "Description", 0, 20, 991, 993);
//    $return = $querys->addCourse($course);
//    echo "<br/>" . $return;*/
//    echo "<br/> Temporarily disabling course creation";
////for testing update course function
//    $return = $querys->updateCourse(1234, "Apples", 123, 02, "Fall 2019",
//            "description", FALSE, 32, 991, 993);
//    echo "<br/>" . $return;
////for testing get course function, based on courseID
//    $return = $querys->getCourse(1113);
//    if (is_object($return))
//        echo "<br/>Returning only CID, Title, Term: " .
//        $return->courseID . ", " . $return->courseTitle . ", " 
//            . $return->term;
//    else
//        echo "<br/>" . $return;
////for testing get all courses function
//    $return = $querys->getAllCourses();
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $course):
//            echo $course[0] . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
//// for testing get instructor courses function, based on instructorID
//    //Is the same output as getAllCourses due to all courses having the same
//    //teacherID temporarily
//    $return = $querys->getInstuctorCourses(993);
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $course):
//            echo $course[0] . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
////for testing get terms function, should return each term in courses one time
//    $return = $querys->getTerms();
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $term):
//            echo $term[0] . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
////for testing get course by term, returns all course id's for specific term
//    $return = $querys->getCoursesOfTerm("Fall 2019");
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $course):
//            echo $course[0] . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
//    
//    
//    
//    
//    
//    
////for testing add assignment function
//    /* $return = $querys->addAssignment("Assignment".rand(1,5),"description",
//      date("Y/m/d"), 1113, 1264, NULL);
//      echo "<br/>" . $return; */
//    echo "<br/>Temporarily disabling assignment creation";
////for testing update assignment function
//    $return = $querys->updateAssignment(1, "Assignment10", "description",
//            date("Y/m/d"), 1113, 993, NULL);
//    echo "<br/>" . $return;
////for testing get individual assignment function, based on assignment id
//    $return = $querys->getAssignment(1);
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $key => $column):
//            if (is_numeric($key) == FALSE)
//                if (is_null($column) == FALSE)
//                    echo $column . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
////for testing retrieving all assignment ids assigned to a course
//    $return = $querys->getAssignments(1113);
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $assignment):
//            echo $assignment[0] . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
////for testing add student assignment function
//    $return = $querys->addStudentAssignment(991, 5, "description", "directory",
//            date("Y/m/d"), "screenshot", FALSE, FALSE);
//    echo "<br/>" . $return;
////for testing updating user assignment function
//    $return = $querys->updateStudentAssignment(991, 5, "desc", "directory",
//            date("Y/m/d"), NULL, NULL, NULL);
//    echo "<br/>" . $return;
//    //for testing get user assignment, based on userID and assignmentID
//    $return = $querys->getStudentAssignemnt(991, 5);
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $key => $column):
//            if (is_numeric($key) == FALSE)
//                if (is_null($column) == FALSE)
//                    echo $column . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
////for testing get student assignments function, returns all of a specific student
//    $return = $querys->getStudentAssignments(991);
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $assignment):
//            echo $assignment[0] . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
////for testing get students of assignment, returns all of a specific assignment
//    $return = $querys->getStudentsOfAssignment(5);
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $students):
//            echo $students[0] . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
////testing add student course function
//    $return = $querys->addStudentCourse(999, 1239, Date("Y/m/d"));
//    echo "<br/>" . $return;
//    //testing get student course, based on student id and course id, may be unnecessary
//    $return = $querys->getStudentCourse(999, 1239);
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $key => $column):
//            if (is_numeric($key) == FALSE)
//                if (is_null($column) == FALSE)
//                    echo $column . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
//    //testing get student courses, which displays all of a specific student
//    $return = $querys->getStudentCourses(999);
//    if (is_array($return))
//    {
//
//        foreach ($return as $studentCourse):
//            echo "<br/>";
//            foreach ($studentCourse as $key => $column):
//                if (is_numeric($key) == FALSE)
//                    if (is_null($column) == FALSE)
//                        echo $column . ", ";
//            endforeach;
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
//    //testing get students enrolled, which displays all of a specific course
//    $return = $querys->getStudentsEnrolled(1239);
//    if (is_array($return))
//    {
//
//        foreach ($return as $students):
//            echo "<br/>";
//            foreach ($students as $key => $column):
//                if (is_numeric($key) == FALSE)
//                    if (is_null($column) == FALSE)
//                        echo $column . ", ";
//            endforeach;
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
//
//
//
//
//
//
//
//
////testing return of encrypted password based on username for login authentication
//    $return = $querys->getUserAuth("username");
//    if (is_array($return))
//    {
//        echo "<br/>";
//        foreach ($return as $key => $column):
//            if (is_numeric($key) == FALSE)
//                echo $column . ", ";
//        endforeach;
//    }
//    else
//        echo "<br/>" . $return;
