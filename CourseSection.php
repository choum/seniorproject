<?php 

    class CourseSection {
      
        $id = "123";
        $number = "CIS 000";
        $thisTerm = "FALL 2000";
        $title = "Computing";
        $instructor = "Dr. Professor";
        
        function __construct() {

        }

        function __construct($courseID , $sectionNumber , $term , $classTitle , $classInstructor) {

            $id = $courseID;
            $number = $sectionNumber;
            $thisTerm = $term;
            $title = $classTitle;
            $instructor = $classInstructor;

        }
    }

?>