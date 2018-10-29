<?php

    class CourseSection {

        $id;
        $number;
        $thisTerm;
        $title;
        $instructor;
        $assignments;

        //constructor
        function __construct($courseID , $sectionNumber , $term , $classTitle , $classInstructor , $aList) {

            $id = $courseID;
            $number = $sectionNumber;
            $thisTerm = $term;
            $title = $classTitle;
            $instructor = $classInstructor;
            $assignments = $alist;

        }
    }

?>
