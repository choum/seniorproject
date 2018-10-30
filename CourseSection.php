<?php

<<<<<<< HEAD
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
=======
    class Course
    {

        public $courseID;
        public $courseTitle;
        public $courseNumber;
        public $courseSection;
        public $term;
        public $description;
        public $closed;
        public $enrollment;
        public $adminID;
        public $teacherID;
>>>>>>> justin

        function __construct($title, $number, $section, $term, $desc, $closed,
                $enroll, $admin, $teacher)
        {
            $this->courseTitle = $title;
            $this->courseNumber = $number;
            $this->courseSection = $section;
            $this->term = $term;
            $this->description = $desc;
            $this->closed = $closed;
            $this->enrollment = $enroll;
            $this->adminID = $admin;
            $this->teacherID = $teacher;
        }
        
        function setID($courseID)
        {
            $this->courseID = $courseID;
        }

    }

?>
