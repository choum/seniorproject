<?php

    include("./SQLHelper.php");
    $db = new SQLHelper();
    $user;
    $username = filter_input(INPUT_GET, "user", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($username == NULL OR $username == FALSE)
    {
        header('Location:' . './index.php');
        die();
    }
    else
    {
        $user = $db->getUser($username);
    }


    if ($user->role != 1)
    {
        echo "<br/>This user does not have a profile.";
        die();
    }
    $loggedIn;
    $dashboardLink = "";
    if (sizeof($_SESSION) > 2)
    {
        if ($_SESSION["logged_in"] == TRUE)
        {
            $loggedIn = TRUE;
            $dashboardLink = "./";
        }
        else
        {
            $loggedIn = FALSE;
        }
    }
    if ($dashboardLink == "")
    {
        $loggedIn = FALSE;
    }
    //basic user info
    $firstName = $user->firstName;
    $lastName = $user->lastName;
    $bio = $user->bio;
    $email = $user->email;
    $website = $user->website;
    $linkedin = $user->linkedin;
    $pictureDir = "./profiles/$username/img/";
    $picture = $user->imageLink;
    if ($picture == 'default-profile.jpg') {
      $pictureDir = './img/';
    }
    $userID = $user->id;
    $assignmentDir = "/cap/student/$username/workspace/";
    //student assignments tied to studentID in student_assignment
    $studentAssignmentids = $db->getStudentAssignments($userID);
    $assignmentids = array();
    foreach ($studentAssignmentids as $studentAssignmentid):
        array_push($assignmentids, $studentAssignmentid[0]);
    endforeach;
    $studentAssignments = array();
    foreach ($assignmentids as $assignmentID):
        $studentAssignment = $db->getStudentAssignment($userID, $assignmentID);
        array_push($studentAssignments, $studentAssignment);
    endforeach;
    //To be used for directory, screenshot, featured, and group. (desc too?)
    //Assignments tied to assignmentIDs in student_assignment
    $courseids = array();
    $assignments = array();
    foreach ($assignmentids as $assignmentID):
        $assignment = $db->getAssignment($assignmentID);
        array_push($courseids, $assignment->courseID);
        array_push($assignments, $assignment);
    endforeach;

    //Courses tied to courseIDs in assignments
    $courses = array();
    foreach ($courseids as $courseID):
        $course = $db->getCourse($courseID);
        $exists = FALSE;
        foreach ($courses as $tempCourse):
            if ($course->courseID == $tempCourse->courseID)
            {
                $exists = true;
            }
        endforeach;
        if ($exists != true)
        {
            array_push($courses, $course);
        }
    endforeach;
    //To be used for course number and title (ex. cis 4270 & "OOP for Business")
    //Featured assignment info
    $featuredAssignment = NULL;
    foreach ($studentAssignments as $studentAssignment):
        if ((int) $studentAssignment[5] === 1)
        {
            $featuredAssignment = $studentAssignment;
        }
    endforeach;
    if ($featuredAssignment == NULL OR $featuredAssignment == FALSE)
    {

    }
    else
    {
        //Also store featured course number, project link/dir, group project
        //description
        $featuredAssignmentID = $featuredAssignment[1];
        $featuredAssignmentName = $db->getAssignment($featuredAssignmentID)->name;
        $featuredCourseID = $db->getAssignment($featuredAssignmentID)->courseID;
        $featuredCourseInfo = $db->getCourse($featuredCourseID);
        $featuredCourseNumber = $featuredCourseInfo->courseNumber;
        $featuredDescription = $db->getAssignment($featuredAssignmentID)->description;
        $featuredPath = $assignmentDir;
        $featuredDirectory = $featuredAssignment[2];
        if(array_search(",", $featuredAssignment)){
            $explodedScreenshots = explode(",", $featuredAssignment[4]);
        }else { $explodedScreenshots = $featuredAssignment[4]; }
        $featuredGroupProject = $featuredAssignment[6];

        $featuredScreenshotDir = "./cap/student/$username/img/$featuredAssignmentID/";
        $tempFSC = array();
        if(is_array($explodedScreenshots)){
            foreach ($explodedScreenshots as $screenshot):
                array_push($tempFSC, "$featuredScreenshotDir$screenshot");
            endforeach;
        }
        elseif ($explodedScreenshots != NULL AND $explodedScreenshots != "") {
            $tempFSC = "$featuredScreenshotDir$explodedScreenshots";
        }
        else{
            $tempFSC = $explodedScreenshots;
        }
        $featuredScreenshots = $tempFSC;
    }
