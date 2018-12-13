<?php
    /*
     * Created By: Justin Crest
     * Description: This file serves as the controller section of the project view.
     * Its purpose is to gather all information necessary in order to display
     * in the project-view file, as shown by the lack of class or functions. 
     * It starts by retrieving the course information, as well as the assignment info for said course.
     * From there it retrieves all the student submissions, including a link the each submissions index page.
     * If a PDF was uploaded on the assignments initial creation, the path to it is gotten here.
     * Information on getCourse, getAssignment, getUserByID, getStudentsOfAssignment, and getStudentAssignment
     * functions are located in the SQLHelper file.
     */
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
        array_push($studentAssignmentDirectorys, "/cap/student/$tempStudent->username/workspace/$tempStudentAssignment[2]/");
        array_push($studentSubmissionDates, $tempStudentAssignment[3]);
        
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
        $pdfLocation = "/cap/$currentInstructor->username/" . $course . "/" . $currentAssignment->pdf;
    endif;
