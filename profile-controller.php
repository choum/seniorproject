<?php
    include("./SQLHelper.php");
    session_start();
    $db = new SQLHelper();
    $user;
    $username = filter_input(INPUT_GET, "user", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if($username == NULL OR $username == FALSE ){
        header('Location:' . './index.php');
        die();
    }
    else{
        $user = $db->getUser($username);

    }


    if($user->role != 1){
        echo "<br/>This user does not have a profile.";
        die();
    }
    $loggedIn;
    $dashboardLink = "";
    if(isset($_SESSION) == FALSE OR isset($_SESSION) == NULL){
        $loggedIn = FALSE;
    }
    else{
        $loggedIn= TRUE;
        if($user->role === 1):
            $dashboardLink = 'dashboard.php';
        elseif($user->role === 2):
            $dashboardLink = 'instructor-dashboard.php';
        elseif($user->role === 3):
            $dashboardLink = 'admin-dashboard.php';
        endif;
    }
    if($dashboardLink == "") { $loggedIn = FALSE; }
    //basic user info
    $firstName = $user->firstName;
    $lastName = $user->lastName;
    $bio = $user->bio;
    $email = $user->email;
    $website = $user->website;
    $linkedin = $user->linkedin;
    $picture = $user->imageLink;
    $userID = $user->id;
    //student assignments tied to studentID in student_assignment
    $studentAssignmentids = $db->getStudentAssignments($userID);
    $assignmentids = array();
    foreach($studentAssignmentids as $studentAssignmentid):
        array_push($assignmentids, $studentAssignmentid[0]);
    endforeach;
    $studentAssignments = array();
    foreach($assignmentids as $assignmentID):
        $studentAssignment = $db->getStudentAssignment($userID, $assignmentID);
        array_push($studentAssignments, $studentAssignment);
    endforeach;
     //To be used for directory, screenshot, featured, and group. (desc too?)

    //Assignments tied to assignmentIDs in student_assignment
    $courseids = array();
    $assignments = array();
    foreach($assignmentids as $assignmentID):
        $assignment = $db->getAssignment($assignmentID);
        array_push($courseids, $assignment[5]);
        array_push($assignments, $assignment);
    endforeach;

    //Courses tied to courseIDs in assignments
    $courses = array();
    foreach($courseids as $courseID):
        $course = $db->getCourse($courseID);
        $exists = FALSE;
        foreach($courses as $tempCourse):
            if($course->courseID == $tempCourse->courseID){$exists = true; }
        endforeach;
        if($exists != true) { array_push($courses, $course); }
    endforeach;
     //To be used for course number and title (ex. cis 4270 & "OOP for Business")

    //Featured assignment info
    $featuredAssignment;
    foreach($studentAssignments as $studentAssignment):
        if($studentAssignment[6] === 1){
            $featuredAssignment = $studentAssignment;
        }
        else{$featuredAssignment = NULL; }
    endforeach;
    if($featuredAssignment == NULL OR $featuredAssignment == FALSE){

    }
    else{
        //Also store featured course number, project link/dir, group project
        //description
        $featuredAssignmentID = $featuredAssignment[1];
        $featuredAssignmentName = $db->getAssignment($featuredAssignmentID)[1];
        $featuredCourseID = $db->getAssignment($featuredAssignmentID)[5];
        $featuredCourseInfo = $db->getCourse($featuredCourseID);
        $screenshots = split('~', $featuredAssignment[6]);

    }