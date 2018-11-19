<?php

    $db = new SQLHelper();
    $currentCourse = $db->getCourse($course);
    $currentAssignment = $db->getAssignment($assignment);
    $currentInstructor = $db->getUserByID($currentCourse->teacherID);
    $studentIDs = $db->getStudentsOfAssignment($assignment);

    $studentNames = array();
    $studentAssignmentDirectorys = array();
    $studentSubmissionDates = array();
    $count = 0;
    
    foreach ($studentIDs as $student)
    {
        $tempStudent = $db->getUserByID($student[0]);
        $tempName = "$tempStudent->firstName $tempStudent->lastName";
        $tempStudentAssignment = $db->getStudentAssignment($student[0], $assignment);


        array_push($studentNames, $tempName);
        array_push($studentAssignmentDirectorys, "/cap/$tempStudent->username/$course/$tempStudentAssignment[3]");
        array_push($studentSubmissionDates, $tempStudentAssignment[4]);
        
        $count++;
    }

    $instructorName = "$currentInstructor->firstName $currentInstructor->lastName";
    
    $courseTitle = $currentCourse->courseTitle;
    $courseNumSection = "CIS $currentCourse->courseNumber.$currentCourse->courseSection";
    $courseTerm = $currentCourse->term;

    $assignmentName = $currentAssignment->name;
    $assignmentDescription = $currentAssignment->description;
    $assignmentType = $currentAssignment->type;
    
    $pdfLocation = "";
    if($currentAssignment->pdf != NULL OR $currentAssignment->pdf != ""):
        $pdfLocation = "/cap/" . $currentInstructor->username . "/" . $course . "/" . $currentAssignment->pdf;
    endif;
