<?php
    include("./SQLHelper.php");
    session_start();
    $db = new SQLHelper();
    $user;
    $username = filter_input(INPUT_GET, "user", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if($username == NULL OR $username == FALSE){
        $username = filter_input(INPUT_POST, "user", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($username == NULL OR $username == FALSE){
            $username = INPUT_SESSION["user"];
        }
    }
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
    if(isset($_SESSION) == FALSE OR isset($_SESSION) == NULL){
        $loggedIn = FALSE;
    }
    else{ $loggedIn=true; }
    //basic user info
    $firstName = $user->firstName;
    $lastName = $user->lastName;
    $bio = $user->bio;
    //$email = $user->email; //DOESN'T EXIST YET ANYWHERE
    //$resume = $user->resume; //DOESN'T EXIST YET ANYWHERE
    $website = $user->website;
    $linkedin = $user->linkedin;
    $picture = $user->imageLink;
    $userID = $user->id;
    echo "User Info:";
    echo "<br/>$loggedIn, $firstName, $lastName, $bio, "//$email, $resume, "
            . "$website, $linkedin, $picture, $userID";
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
    echo "<br/>Student Submission Info: Includes, ss, group, and featured. (Maybe null)";
    foreach($studentAssignments as $studentAssignment):
        echo "<br/>";
        foreach($studentAssignment as $key=>$column):
            if(is_numeric($key)){ echo $column . " "; }
        endforeach;
    endforeach;
    //Assignments tied to assignmentIDs in student_assignment
    $courseids = array();
    $assignmentNames = array();
    foreach($assignmentids as $assignmentID):
        $assignment = $db->getAssignment($assignmentID);
        array_push($courseids, $assignment[5]);
        array_push($assignmentNames, $assignment[1]);
    endforeach;
    
    //Courses tied to courseIDs in assignments
    $courses = array();
    foreach($courseids as $courseID):
        $course = $db->getCourse($courseID);
        array_push($courses, $course);
    endforeach;
     //To be used for course number and title (ex. cis 4270 & "OOP for Business")
    echo "<br/>Course Info: Course name, number, section, etc.";
    foreach($courses as $course):
        echo "<br/>";
        foreach($course as $column):
                echo $column . " ";
        endforeach;
    endforeach;
    //Featured assignment info
    $featuredAssignment;
    foreach($studentAssignments as $studentAssignment):
        if($studentAssignment[6] === 1){
            $featuredAssignment = $studentAssignment;
        }
        else{$featuredAssignment = NULL; }
    endforeach;
    if($featuredAssignment == NULL OR $featuredAssignment == FALSE){
        echo "<br/>No featured assignment";
    }
    else{
        $featuredAssignmentID = $featuredAssignment[1];
        $featuredAssignmentName = $db->getAssignment($featuredAssignmentID)[1];
        $featuredCourseID = $db->getAssignment($featuredAssignmentID)[5];
        $featuredCourseInfo = $db->getCourse($featuredCourseID);
        $screenshots = split('~', $featuredAssignment[6]);

        echo "<br/>$featuredAssignmentID, $featuredAssignmentName, "
                . "$featuredCourseID, $featuredCourseInfo, $screenshots";
    }