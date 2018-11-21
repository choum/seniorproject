<?php

    require_once "SQLHelper.php";
        
    $userID = $current_user->id;
    $aboutMe = $current_user->bio;
    $resume = $current_user->resume;
    $portfolio = $current_user->portfolio;
    $picture = $current_user->picture;
    
    $db = new SQLHelper();
    $courses = $db->getStudentCourses($userID);
    
    $coursesInfo = array();
    if($courses != NULL):
        foreach($courses as $courseID):
            $tempCourse = $db->getCourse($courseID);
            array_push($coursesInfo, $tempCourse);
        endforeach;
    endif;
    
    $assignmentsOfCourses = array();
    
    if($courses != NULL):
        foreach($courses as $courseID):
            $tempAssignments = $db->getAssignments($courseID);
            foreach($tempAssignments as $assignmentID):
                $tempAssignment = $db->getAssignment($assignmentID);
                array_push($assignmentsOfCourses[$courseID], $tempAssignment);
            endforeach;
        endforeach;
    endif;
    
    $action = filter_input(INPUT_POST, 'action');
    
    if($action == 'addClass'){
        addClass();
    }else if($action == 'updateProfile'){
        updateProfile();
    }else if ($action == 'uploadProject'){
        uploadProject();
    }
    
    function addClass(){
        $courseKey = filter_input(INPUT_POST, 'addClass');
        $db = new SQLHelper();
        $userID = $db->getUser($_SESSION['user'])->id;
        $db->addUsingCourseKey($userID, $courseKey);
    }
    
    function updateProfile(){
        $aboutMe = filter_input(INPUT_POST, 'aboutMe');
        $resume = filter_input(INPUT_POST, 'resume');
        $portfolio = filter_input(INPUT_POST, 'portfolio');
        $previousPicture = filter_input(INPUT_POST, 'prevPicture');
        $newPicture = filter_input(INPUT_POST, 'newPicture');
        
        $imageLink = NULL;
        if($newPicture == NULL)
            $imageLink = $previousPicture;
        else
            $imageLink = $newPicture;
        
        $db = new SQLHelper();
        $userID = $db->getUser($_SESSION['user'])->id;
        $db->updateUser($userID, $aboutMe, $imageLink, $resume, $portfolio);
    }
    
    function uploadProject(){
        $currentAssignment = filter_input(INPUT_POST, 'assignment');
        $projectFiles = filter_input(INPUT_POST, 'projectFiles');
        $pictures = filter_input(INPUT_POST, 'pictures');
        $featured = filter_input(INPUT_POST, 'featured');
        $group = filter_input(INPUT_POST, 'group');
        
        $db = new SQLHelper();
        $userID = $db->getUser($_SESSION['user'])->id;
        $db->addStudentAssignment(userID, $currentAssignment, $desc, $dir, date_create('TODAY'), $pictures, $featured, $group);
    }
    
    
    
