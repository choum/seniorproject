<?php

    class User
    {

        public $id;
        public $username;
        public $password;
        public $firstName;
        public $lastName;
        public $title;
        public $bio;
        public $email;
        public $imageLink;
        public $linkedin;
        public $website;
        public $role;
        public $suspended;
<<<<<<< HEAD
        public $coursesEnrolled;
=======
        public $courses;
>>>>>>> master
        public $dateCreated;
        public $lastLoginDate;

        //constructor with all the info
<<<<<<< HEAD
        function __construct($userID, $user, $password, $first, $last, $title, $bio, $email,
                $image, $linked, $site, $role, $suspended, $coursesEnrolled, $create, $lastlog)
=======
        function __construct($user, $password, $first, $last, $title, $bio,
                $email, $image, $linked, $site, $role, $suspended, $create, $lastlog)
>>>>>>> master
        {
            $this->id = $userID;
            $this->username = $user;
            $this->password = $password;
            $this->firstName = $first;
            $this->lastName = $last;
            $this->title = $title;
            $this->email = $email;
            $this->bio = $bio;
            $this->email = $email;
            $this->imageLink = $image;
            $this->linkedin = $linked;
            $this->website = $site;
            $this->role = $role;
            $this->suspended = $suspended;
            $this->coursesEnrolled = $coursesEnrolled;
            $this->dateCreated = $create;
            $this->lastLoginDate = $lastlog;
        }

<<<<<<< HEAD
=======
        function setID($userID)
        {
            $this->id = $userID;
        }
        
        function setCourses($courses)
        {
            $this->courses = $courses;
        }

>>>>>>> master
    }

?>
