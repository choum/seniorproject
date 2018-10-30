<?php

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